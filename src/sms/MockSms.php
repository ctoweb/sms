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
 * 测试短信
 * Class MockSms
 * @package Aix\Sms
 * Auth Zhong
 * Date 2018/11/2
 */
class MockSms implements Smser
{


    /**
     * 发送基本短信
     * @param string $mobile
     * @param SmsTemplate $template
     * @param array $params
     * @return bool
     */
    public function sendSms(string $mobile, SmsTemplate $template, array $params = []): bool
    {


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
        $params['code']=123456;
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