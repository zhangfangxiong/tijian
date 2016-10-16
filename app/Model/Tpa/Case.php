<?php

class Model_Tpa_Case extends Model_Tpa_Base
{
    const TABLE_NAME = 't_case';

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
        $sCaseCode = $prefix . Util_Tools::passwdGen(8, 1);
        if (self::getCaseByCode($sCaseCode)) {
            self::initCode();
        }
        return $sCaseCode;
    }

    // 根据案件编号获取案件
    public static function getCaseByCode($sCaseCode, $iStatus = null)
    {
        $aWhere = array(
            'sCode' => $sCaseCode
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

    //批量标记为已打印
    public static function printCase($iIDs,$iUserID)
    {
        if (is_array($iIDs)) {
            $iIDs = implode(',',$iIDs);
        }
        $sBatchNumber = date("YmdHis",time());
        $sSql = 'UPDATE '.self::DB_NAME.'.'.self::TABLE_NAME.' SET iUpdateUserID='.$iUserID.',iUpdateTime='.time().',sBatchNumber = '.$sBatchNumber.' WHERE iAutoID IN ('.$iIDs.')';
        return self::query($sSql);
    }
}