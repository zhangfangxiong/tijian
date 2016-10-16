<?php

class Model_Tpa_Company extends Model_Tpa_Base
{
    const TABLE_NAME = 't_company';

    /**
     * 生成公司编号（自动生成的）
     * @param string $prefix 前缀
     * @return string
     */
    public static function initCompanyCode($prefix = 0)
    {
        // 生成规则未定？
        // p-46634562
        $prefix = $prefix == 0 ? 'U' : $prefix;
        $sCompanyCode = $prefix . Util_Tools::passwdGen(8, 1);
        if (self::getCompanyByCode($sCompanyCode)) {
            self::initCompanyCode();
        }
        return $sCompanyCode;
    }

    // 根据公司编号获取公司
    public static function getCompanyByCode($sCompanyCode, $iStatus = null)
    {
        $aWhere = array(
            'sCompanyCode' => $sCompanyCode
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