<?php

class Model_Tpa_Employee extends Model_Tpa_Base
{
    const TABLE_NAME = 't_employee_base';

    /**
     * 生成雇员编号（自动生成的）
     * @param string $prefix 前缀
     * @return string
     */
    public static function initEmployeeCode($prefix = 0)
    {
        // 生成规则未定？
        // p-46634562
        $prefix = $prefix == 0 ? 'U' : $prefix;
        $sEmployeeCode = $prefix . Util_Tools::passwdGen(8, 1);
        if (self::getEmployeeByCode($sEmployeeCode)) {
            self::initEmployeeCode();
        }
        return $sEmployeeCode;
    }

    // 根据雇员编号获取雇员
    public static function getEmployeeByCode($sEmployeeCode, $iStatus = null)
    {
        $aWhere = array(
            'sCode' => $sEmployeeCode
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

    // 根据雇员身份证+姓名获取雇员
    public static function getEmployeeByIDAndName($sIdentityCard,$sRealName, $iStatus = null)
    {
        $aWhere = array(
            'sIdentityCard' => $sIdentityCard,
            'sRealName' => $sRealName
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