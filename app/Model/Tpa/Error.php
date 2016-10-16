<?php

class Model_Tpa_Error extends Model_Tpa_Base
{
    const TABLE_NAME = 't_case_error';

    /**
     * 生成受理问题单编号（自动生成的）
     * @param string $prefix 前缀
     * @return string
     */
    public static function initCode($prefix = 0)
    {
        // 生成规则未定？
        // p-46634562
        $prefix = $prefix == 0 ? 'ae' : $prefix;
        $sErrorCode = $prefix . Util_Tools::passwdGen(8, 1);
        if ($aData = self::getErrorByCode($sErrorCode)) {
            self::initCode();
        }
        return $sErrorCode;
    }

    // 根据受理问题单编号获取受理问题单
    public static function getErrorByCode($sErrorCode, $iStatus = null)
    {
        $aWhere = array(
            'sCode' => $sErrorCode
        );
        if ($iStatus === null) {
            $aWhere['iStatus >'] = 0;
        } else {
            $aWhere['iStatus'] = $iStatus;
        }
        return self::getRow(array(
            'where' => $aWhere
        ));
    }
}