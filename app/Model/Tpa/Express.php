<?php

class Model_Tpa_Express extends Model_Tpa_Base
{
    const TABLE_NAME = 't_express';
    const NOPRINT = 1;//未打印
    const HASPRINT = 2;//已打印
    const TYPESELF = 1;//自交件
    const TYPEEXPRESS = 2;//快递收入


    /**
     * 生成快递编号（自动生成的）
     * @param string $prefix 前缀
     * @return string
     */
    public static function initExpressCode($prefix = 0)
    {
        // 生成规则未定？
        // p-46634562
        $prefix = $prefix == 0 ? 'p' : $prefix;
        $sExpressCode = $prefix . Util_Tools::passwdGen(8, 1);
        if (self::getExpressByCode($sExpressCode)) {
            self::initExpressCode();
        }
        return $sExpressCode;
    }

    // 根据快递编号获取产品
    public static function getExpressByCode($sExpressCode, $iStatus = null)
    {
        $aWhere = array(
            'sExpressCode' => $sExpressCode
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
    public static function printExpress($iIDs,$iUserID)
    {
        if (is_array($iIDs)) {
            $iIDs = implode(',',$iIDs);
        }
        $sSql = 'UPDATE '.self::DB_NAME.'.'.self::TABLE_NAME.' SET iUpdateUserID='.$iUserID.',iUpdateTime='.time().',iStatus = '.self::HASPRINT.' WHERE iAutoID IN ('.$iIDs.')';
        return self::query($sSql);
    }
}