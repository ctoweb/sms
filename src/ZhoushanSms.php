<?php
namespace Aix\Sms;


use Aix\Sms\Contracts\Smser;
use App\Models\SmsTemplate;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ZhoushanSms implements Smser
{
    /**
     * @var Client
     */
    private $httpClient;
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
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->sign_key = $sign_key;

        $this->httpClient=new Client([
            'http_errors' => false
        ]);
    }


    /**
     * 发送基本短信
     * @param string $mobile
     * @param SmsTemplate $template
     * @param array $params
     * @return bool
     */
    public function sendSms(string $mobile, SmsTemplate $template, array $params = []): bool
    {
        $time=(int)(microtime(true)*1000);
        $id=$time;
        $appKey=$this->app_key;
        $phoneNums=$mobile;
        $content="【舟山市就业局】".$template->value;
        $flag=1;
        $wsdlUrl="http://115.236.191.137:8080/APL-SMSService/SMSService?wsdl";
        $post_data = "<SOAP-ENV:Envelope xmlns:ns0='http://ws.sms.zjapl.com' xmlns:ns1='http://schemas.xmlsoap.org/soap/envelope/'  xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'>"
            . "<SOAP-ENV:Header/>"
            . "<ns1:Body>"
            . "<ns0:sendSms >"
            . "<id>" . $id . "</id>"
            . "<appKey>" . $appKey . "</appKey>"
            . "<phoneNums>" . $phoneNums . "</phoneNums>"
            . "<content>" . $content . "</content>"
            . "<time>" . $time . "</time>"
            . "<flag>" . $flag . "</flag>"
            . "</ns0:sendSms>"
            . "</ns1:Body>"
            . "</SOAP-ENV:Envelope>";

        $response=$this->httpClient->post($wsdlUrl, ['body'=>$post_data]);
        return true;
    }

    /**
     * 发送确认短信,需要缓存验证码code
     * @param string $mobile
     * @param SmsTemplate $template
     * @return bool
     */
    public function sendAuthSms(string $mobile, SmsTemplate $template): bool
    {
        $params['code']=rand(100000, 999999);
        $template->value=render_template($template->value, $params);
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
        //
    }
}