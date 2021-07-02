<?php
namespace Aix\Sms;

use Aix\Sms\Contracts\Smser;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Cache;

/**
 * 啊里云短信
 * Class AliSms
 * @package Aix\Sms
 * Auth Zhong
 * Date 2018/11/2
 */
class AliSms implements Smser
{

    /**
     * @var DefaultAcsClient
     */
    protected static $acsClient;
    private $app_key;
    private $app_secret;
    private $sign_key;

    /**
     * SmsService constructor.
     * @param $app_key
     * @param $app_secret
     * @param $sign_key
     */
    public function __construct($app_key, $app_secret, $sign_key)
    {
        Config::load();
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->registerAcsClient();
        $this->sign_key = $sign_key;
    }

    /**
     * 取得AcsClient
     *
     * @return void
     */
    private function registerAcsClient()
    {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        if (static::$acsClient == null) {
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $this->app_key, $this->app_secret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
    }

    /**
     * 发送基本短信
     * @param string $mobile
     * @param SmsTemplate $template
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function sendSms(string $mobile, SmsTemplate $template, array $params = []): bool
    {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($this->sign_key);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($template->outer_template_id);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        if (!empty($params)) {
            $request->setTemplateParam(json_encode($params, JSON_UNESCAPED_UNICODE));
        }
        // 可选，设置流水号
        //$request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        //$request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::$acsClient->getAcsResponse($request);
        if ($acsResponse->Message != 'OK') {
            throw new \Exception($acsResponse->Message);
        }

        return true;
    }

    /**
     * 发送确认短信,需要缓存验证码code
     * @param string $mobile
     * @param SmsTemplate $template
     * @return bool
     * @throws \Exception
     */
    public function sendAuthSms(string $mobile, SmsTemplate $template): bool
    {
        $params['code']=rand(100000, 999999);
        $this->sendSms($mobile, $template, $params);
        Cache::put($template->alias.'_'.$mobile, $params['code'], 15);
        return true;
    }

    /**
     * 批量发送短信
     * @param array $mobiles
     * @param SmsTemplate $template
     * @param array $params
     * @return bool
     */
    public function sendBatchSms(array $mobiles, SmsTemplate $template, array $params = []): bool
    {
        return true;
    }
}