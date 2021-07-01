<?php
namespace Aix\Sms\Contracts;

use App\Models\SmsTemplate;

/**
 * 短信契约,新的短实例需要实现该接口
 * Interface Smser
 * @package Aix\Sms\Contracts
 */
interface Smser
{

    /**
     * 用户验证身份短信模板
     */
    const TEMPLATE_TEST="sms_testing";

    /**
     * 用户验证身份短信模板
     */
    const TEMPLATE_AUTH_CHECK="sms_auth_check";
    /**
     * 用户登录短信模板
     */
    const TEMPLATE_AUTH_LOGIN="sms_auth_login";
    /**
     * 用户注册短信模板
     */
    const TEMPLATE_AUTH_REGISTER="sms_auth_register";

    /**
     * 用户申请职位模板
     */
    const TEMPLATE_JOB_APPLY="sms_applyjobs";
    /**
     * 面试邀请模板
     */
    const TEMPLATE_JOB_INVITE="sms_invite";
    /**
     * 申请充值
     */
    const TEMPLATE_SMS_ORDER ='sms_order';
    /**
     * 快速注册
     */
    const TEMPLATE_SMS_QUICK_REGISTER = 'sms_quick_register';
    /**
     * 重置密码
     */
    const TEMPLATE_SMS_EDITPWD = 'sms_editpwd';
    /**
     *营业执照审核通过
     */
    const TEMPLATE_SMS_LICENSEALLOW = 'sms_licenseallow';
    /**
     * 营业执照审核未通过
     */
    const TEMPLATE_SMS_LICENSENOTALLOW = 'sms_licensenotallow';

    /**
     * 职位审核通过
     */
    const TEMPLATE_SMS_JOBSALLOW = 'sms_jobsallow';

    /**
     * 职位审核未通过
     */
    const TEMPLATE_SMS_JOBSNOTALLOW = 'sms_jobsnotallow';


    /**
     * 招聘会预定成功
     */
    const TEMPLATE_JOBFAIR_APPLY_OK="sms_jobfair_apply_ok";

    /**
     * 招聘会预定失败
     */
    const TEMPLATE_JOBFAIR_APPLY_ERROR="sms_jobfair_apply_error";
    /**
     * 视频面试邀请
     */
    const TEMPLATE_VIDEO_INTERVIEW="sms_video_interview";

    /**
     * 发送基本短信
     * @param string $mobile
     * @param SmsTemplate $template
     * @param array $params
     * @return bool
     */
    public function sendSms(string $mobile, SmsTemplate $template, array $params = []): bool ;

    /**
     * 发送确认短信,需要缓存验证码code
     * @param string $mobile
     * @param SmsTemplate $template
     * @return bool
     */
    public function sendAuthSms(string $mobile, SmsTemplate $template): bool ;

    /**
     * 批量发送短信
     * @param array $mobiles
     * @param SmsTemplate $template
     * @param array $params
     * @return bool
     */
    public function sendBatchSms(array $mobiles, SmsTemplate $template, array $params = []): bool ;
}