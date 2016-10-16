<?php

class Model_Tpa_Dept extends Model_Tpa_Base
{
    
    const TABLE_NAME = 't_department';
    
    /**
     * 通过部门编号获取信息
     * @param string $sNumber
     * @return array
     */
    public static function getDeptByNumber ($sNumber)
    {
        return self::getRow(['where' => [
            'iStatus' => self::STATUS_VALID,
            'sNumber' => $sNumber
        ]]);
    }
    
    //根据部门下所有子孙部门id
    public static function getSubDeptIDs ($iDeptID)
    {
        $aWhere = [
            'iParentID' => $iDeptID, 
            'iStatus' => self::STATUS_VALID
        ];
        
        $aDept = self::getPair(['where' => $aWhere], 'iAutoID', 'iAutoID');
        $sDeptIDs = implode(',', $aDept);
        
    	return $sDeptIDs;
    }
    
    public static function getTree ($aWhere = [])
    {    	   
        $aList = self::getAll(array(
            'where' => $aWhere,
            'order' => 'iAutoID ASC'
        ));        
        
        // 排序及整理
        $aParentID = $aOrder = $aData = array();
        foreach ($aList as $aMenu) {
            $aParentID[] = $aMenu['iParentID'];
            $aOrder[] = 0;
            unset($aMenu['iStatus'], $aMenu['iCreateTime'], $aMenu['iUpdateTime']);
            $aData[] = $aMenu;
        }
        unset($aList);
        array_multisort($aParentID, SORT_NUMERIC, SORT_ASC, $aOrder, SORT_NUMERIC, SORT_ASC, $aData);

        // 整理父节点
        $aParent = [];
        foreach ($aData as $aMenu) {            
            $aParent[$aMenu['iParentID']][] = $aMenu;
        }
        unset($aMenu);
        
        return self::_buildTree($aParent, 0, 0);
    }

    private static function _buildTree ($aParent, $iParentID, $iLevel)
    {
        $aTree = array();
        if (isset($aParent[$iParentID])) {        	
            foreach ($aParent[$iParentID] as $aMenu) {             	
            	$aMenu['iLevel'] = $iLevel;      
                $aMenu['aChild'] = self::_buildTree($aParent, $aMenu['iAutoID'], $iLevel + 1);
                $aTree[] = $aMenu;
            }
        }

        return $aTree;
    }
    
    //部门编号 自动生成
    public static function initDeptCode()
    {
        $sCode = 'ZY_'.Util_Tools::passwdGen(4,1);
        if(self::getDeptByNumber($sCode)) {
            self::initDeptCode();
        }
        return $sCode;
    }
    
}
