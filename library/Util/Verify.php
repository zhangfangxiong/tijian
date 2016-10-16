<?php

/**
 * 验证码
 * @author len
 *
 */
class Util_Verify
{
    const TYPE_AD_IMAGE = 1;
    const TYPE_MEDIA_IMAGE = 2;
    const TYPE_FORGET_IMAGE = 3;
    const TYPE_REGISTER_IMAGE = 9;//注册验证码
    const TYPE_LOGIN_IMAGE = 5;//账号登陆验证码
    const TYPE_CARD_LOGIN_IMAGE = 9;//卡号登陆验证码
    const TYPE_CARD_ADD_IMAGE_WX = 7;//微信添加卡号验证码

    const TYPE_SMS_FORGET = 11;//忘记密码  实为入职体检
    const TYPE_SYS_REGISTER = 10;//注册手机验证码
    const TYPE_SYS_LOGIN = 9;//登陆手机验证
    const TYPE_SYS_BIND_WX = 9;//微信绑定手机验证码
    const SEND_SYS_LIMIT = 60;//发送间隔

    /**
     * 获取图片验证码
     * @param unknown $iType
     * @return string
     */
    public static function makeImageCode($iType)
    {
        $sKey = self::getImageKey($iType);
        $sRand = Util_Tools::passwdGen(4);
        Util_Cookie::set($sKey, $sRand, 1800);
        return Util_Image::createIdentifyCodeImage(120, 50, $sRand);
    }

    /**
     * 取得验证码Cookie名
     *
     * @param int $iType
     * @return string
     */
    protected static function getImageKey($iType)
    {
        $sGuid = Util_Cookie::get('guid');
        return $sGuid . '_' . $iType;
    }

    /**
     * 检测验证码是否正确
     * @param unknown $iType
     * @param unknown $sCode
     * @return bool
     */
    public static function checkImageCode($iType, $sCode)
    {
        $sKey = self::getImageKey($iType);
        $sSaveCode = Util_Cookie::get($sKey);
        Util_Cookie::delete($sKey);
        return strtoupper($sCode) == strtoupper($sSaveCode);
    }

    /**
     * 取得验证码Cookie名
     *
     * @param int $iType
     * @return string
     */
    protected static function getSmsKey($iType)
    {
        $sGuid = Util_Cookie::get('guid');
        return $sGuid . 's_' . $iType;
    }

    /**
     * 取得短信验证码时间间隔Cookie名
     *
     * @param int $iType
     * @return string
     */
    protected static function getSmsLimitKey($iType)
    {
        $sGuid = Util_Cookie::get('guid');
        return $sGuid . 's_l_' . $iType;
    }

    /**
     * 发送手机验证码
     * @param unknown $sMobile
     * @param unknown $iType
     */
    public static function makeSMSCode($sMobile, $iType)
    {
        $sLimitKey = self::getSmsLimitKey($iType);
        if (!Util_Cookie::get($sLimitKey)) {
            $sKey = self::getSmsKey($iType);
            $sRand = Util_Tools::passwdGen(4, Util_Tools::FLAG_NUMERIC);
            Util_Cookie::set($sKey, $sRand, 1800);
            $iTempID = Util_Common::getConf($iType, 'aSmsTempID');
            $aRet = Sms_CCP::sendTemplateSMS($sMobile, array($sRand, 10), $iTempID);
            if ($aRet['status']) {
                Util_Cookie::set($sLimitKey, time(), self::SEND_SYS_LIMIT);
            }
            return $aRet;
        } else {
            $aRet['status'] = 1;
            return $aRet;
        }
    }

    /**
     * 检验验证码
     * @param unknown $sMobile
     * @param unknown $iType
     * @param unknown $sCode
     */
    public static function checkSMSCode($iType, $sCode)
    {
        $sKey = self::getSmsKey($iType);
        $sSaveCode = Util_Cookie::get($sKey);
        
        if (strtoupper($sCode) == strtoupper($sSaveCode)) {
            Util_Cookie::delete($sKey);    
            return 1;
        } else {
            return 0;
        }
    }
}