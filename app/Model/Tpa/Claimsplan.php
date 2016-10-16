<?php

class Model_Tpa_Claimsplan extends Model_Tpa_Base
{
	const TABLE_NAME = 't_case_claimsplan';

    /**
     * 生成案件编号（自动生成的）
     * @param string $prefix 前缀
     * @return string
     */
    public static function initCode($prefix = 0)
    {
        // 生成规则未定？
        // p-46634562
        $prefix = $prefix == 0 ? 'C' : $prefix;
        $sClaimsplanCode = $prefix . Util_Tools::passwdGen(8, 1);
        if (self::getClaimsplanByCode($sClaimsplanCode)) {
            self::initCode();
        }
        return $sClaimsplanCode;
    }

    // 根据案件编号获取案件
    public static function getClaimsplanByCode($sClaimsplanCode, $iStatus = null)
    {
        $aWhere = array(
            'sCode' => $sClaimsplanCode
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