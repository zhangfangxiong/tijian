<?php

class Controller_Tpa_Admin_Index extends Controller_Tpa_Admin_Base
{

    protected $aLoginType = [
        1 => [
            'url' => '/tpa/admin/login',
            'name' => 'admin后台'
        ]
    ];

    protected $bCheckLogin = false;

    public function actionBefore ()
    {
        parent::actionBefore();
        
        $this->assign('sSignUrl', '/tpa/admin/signin');
        $this->assign('sLogoutUrl', '/tpa/admin/logout');
    }

    public function indexAction ()
    {
        // 当前用户
        $this->aCurrUser = Util_Cookie::get(Yaf_G::getConf('authkey', 'cookie', 'tpa'));
        if (!$this->aCurrUser) {
            $sUrl = '/tpa/admin/login';
            return $this->redirect($sUrl);
        }
        
        $this->assign('aCurrUser', $this->aCurrUser);
    }

    //admin后台登陆
    public function loginAction ()
    {
        $iType = 1;
        $this->assign('iType',$iType);
        $this->assign('aLoginType',$this->aLoginType);
    }

    public function logoutAction ()
    {
        $logout = '/tpa/admin/login';
        Util_Cookie::delete(Yaf_G::getConf('authkey', 'cookie', 'tpa'));
        $this->redirect($logout);
    }

    public function signinAction ()
    {
        $sAdminName = $this->getParam('username');
        $sPassword = $this->getParam('password');
        $bRemember = $this->getParam('remember');
        $iType = $this->getParam('type') ? intval($this->getParam('type')) : 1;//可以各个后台登陆
        $aUser = Model_Tpa_Admin::getUserByUserName($sAdminName,$iType);
        if (empty($aUser)) {
            return $this->showMsg('帐号不存在！', false);
        }
        if ($aUser['iIsCheck'] == Model_Tpa_Admin::NOCHECK) {
            return $this->showMsg('帐号待审核！', false);
        }
        if ($aUser['iIsCheck'] == Model_Tpa_Admin::REFUSE) {
            return $this->showMsg('帐号未审核通过！', false);
        }
        if ($aUser['iStatus'] == Model_Tpa_Admin::STATUS_TYPE_LOCK) {
            return $this->showMsg('帐号被禁用！', false);
        }
        if ($aUser['iStatus'] == Model_Tpa_Admin::STATUS_TYPE_LEAVE) {
            return $this->showMsg('该员工已离职！', false);
        }
        if ($aUser['iStatus'] == Model_Tpa_Admin::STATUS_TYPE_DELETE) {
            return $this->showMsg('该账号已删除！', false);
        }
        if ($aUser['sPassword'] != md5(Yaf_G::getConf('cryptkey', 'cookie', 'tpa') . $sPassword)) {
            return $this->showMsg('密码不正确！', false);
        }
        
        $aCookie = array(
            'iUserID' => $aUser['iUserID'],
            'sUserName' => $aUser['sUserName'],
            'sRealName' => $aUser['sRealName'],
            'iType' => $aUser['iType'],//用户类型
        );
        if ($bRemember) {
            $expire = 86400 * 7;
        } else {
            $expire = 0;
        }
        
        Util_Cookie::set(Yaf_G::getConf('authkey', 'cookie', 'tpa'), $aCookie, $expire);
        return $this->showMsg([
            'msg' => '登录成功！',
            'sUrl' => '/tpa/admin/'
        ], true);
    }

    public function permissionAction ()
    {
        $aMenuList = Model_Menu::getMenus();
        
        $aCtrClass = array();
        $aMenuAction = array();
        foreach ($aMenuList as $aMenu) {
            if ($aMenu['bIsLeaf']) {
                $aRoute = Yaf_G::getRoute($aMenu['sUrl']);
                $aMenuAction[$aRoute['module'] . '_' . $aRoute['controller'] . '_' . $aRoute['action']] = $aMenu['sMenuName'];
                $aCtrClass[$aRoute['module'] . '_' . $aRoute['controller']] = array(
                    'iMenuID' => $aMenu['iMenuID'],
                    'sMenuName' => $aMenu['sMenuName'],
                    'sUrl' => $aMenu['sUrl']
                );
            }
        }
        
        $aPermission = array();
        foreach ($aCtrClass as $sCtrClass => $aMenu) {
            try {
                $sCtrClass = 'Controller_' . $sCtrClass;
                if (class_exists($sCtrClass)) {
                    $oCtr = new ReflectionClass($sCtrClass);
                    $aMethod = $oCtr->getMethods();
                    foreach ($aMethod as $oMethod) {
                        $sAction = $oMethod->getName();
                        if (substr($sAction, - 6) === 'Action') {
                            $sAction = substr($sAction, 0, - 6);
                            $aRow = array(
                                $aMenu['iMenuID']
                            );
                            $aRow[] = Yaf_G::routeToUrl($sCtrClass . '_' . $sAction);
                            $sDoc = $oMethod->getDocComment();
                            $matches = null;
                            if (preg_match('/\s+\*\s+(.+)/i', $sDoc, $matches)) {
                                $aRow[] = $matches[1];
                            } elseif (isset($aMenuAction[$sCtrClass . '_' . $sAction])) {
                                $aRow[] = $aMenuAction[$sCtrClass . '_' . $sAction];
                            } else {
                                $aRow[] = $aMenu['sMenuName'] . '::' . $sAction;
                            }
                            $aPermission[] = $aRow;
                        }
                    }
                }
            } catch (Exception $e) {
                $aPermission[] = array(
                    $aMenu['iMenuID'],
                    Yaf_G::getUrl($aMenu['sUrl']),
                    $aMenu['sMenuName']
                );
            }
        }
        $this->showMsg($aPermission, true);
    }
}