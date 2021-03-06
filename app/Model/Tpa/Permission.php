<?php

class Model_Tpa_Permission extends Model_Tpa_Base
{

    const TABLE_NAME = 't_permission';

    /**
     * 删除权限点
     * 
     * @param int $iPermissionID            
     * @return int
     */
    public static function delData ($iPermissionID)
    {
        $oOrm = self::getOrm();
        $oOrm->setPKIDValue($iPermissionID);
        return $oOrm->delData();
    }

    /**
     * 取一个项目的所有权限点
     * 
     * @return array
     */
    public static function getAllPermissions ()
    {
        $aRet = array();
        $aList = self::getAll(array(), true);
        foreach ($aList as $v) {
            $aRet[$v['iMenuID']][] = $v;
        }
        
        return $aRet;
    }

    /**
     * 取得用户所有菜单的权限
     * 
     * @param array $aUser            
     * @return array
     */
    public static function getMenuPermissions ($iAdminID)
    {
        $aUser = Model_User::getDetail($iAdminID);
        if ($aUser['iRoleID'] === '0') {
            return - 1;
        }
        $aRoleList = Model_Tpa_Role::getPKIDList($aUser['iRoleID']);
        $aModuleID = array();
        foreach ($aRoleList as $aRole) {
            if (! empty($aRole['sModule'])) {
                $aModuleID = array_merge($aModuleID, explode(',', $aRole['sModule']));
            }
        }
        return array_flip($aModuleID);
    }

    /**
     * 取得用户所有访问权限
     * 
     * @param array $aUser            
     * @return array
     */
    public static function getUserPermissions ($iAdminID)
    {
        $aUser = Model_User::getDetail($iAdminID);
        if ($aUser['iRoleID'] === '-1') {
            return - 1;
        }
        $aRoleList = Model_Tpa_Role::getPKIDList($aUser['iRoleID']);
        $aPermissionID = array();
        foreach ($aRoleList as $aRole) {
            if (! empty($aRole['sPermission'])) {
                $aPermissionID = array_merge($aPermissionID, explode(',', $aRole['sPermission']));
            }
        }
        $aPermission = array();
        $aList = self::getPKIDList($aPermissionID, true);
        foreach ($aList as $v) {
            $aPermission[$v['sPath']] = 1;
        }
        return $aPermission;
    }
}