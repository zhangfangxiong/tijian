<?php

/**
 * 企业后台_架构设置
 * @author xuchuyuan
 */

class Controller_Tpa_Admin_Dept extends Controller_Tpa_Admin_Base
{

    const  ERROR = '不能为空';

    public $departmentId = null;

    public $page = null;
    
    public $aDept = null;
    
    public $aDeptUrl = [
        'sDeptUrl'      => '/tpa/admin/dept/index',
        'sDeptAddUrl'   => '/tpa/admin/dept/add',
        'sDeptEditUrl'  => '/tpa/admin/dept/edit',
        'sDeptDelUrl'   => '/tpa/admin/dept/del',
    ];

    public function assignUrl ()
    {
        foreach ($this->aDeptUrl as $k => $v) {
            $this->assign($k, $v);
        }
    }

    public function actionBefore() {
        parent::actionBefore(); 
        
        $this->departmentId = $this->getParam('iDeptID') ? intval($this->getParam('iDeptID')) : 1;
        if ($this->departmentId) {
            $this->aDept = Model_Tpa_Dept::getDetail($this->departmentId);
        }
        
        $aTree = Model_Tpa_Dept::getTree();
        
        $this->assign('aTree', $aTree);    
        $this->assign('iDeptID', $this->departmentId);
        $this->assign('aDepartment', $this->aDept);
        $this->assignUrl();
    }

    /**
     * @return [array]
     */
    public function indexAction ()
    {
        $page = max(intval($this->getParam('page')), 1);
        
        $sDeptIDs = Model_Tpa_Dept::getSubDeptIDs($this->departmentId);
        $sDeptIDs ? $sDeptIDs .= ',' . $this->departmentId : $sDeptIDs = $this->departmentId;
        
        $aWhere = ['iDeptID IN' => $sDeptIDs];
        $aAdmin = Model_Tpa_Admin::getList($aWhere, $page);
        $aAdmin = $this->getAdminBaseInfoList($aAdmin);
        
        $this->assign('aAdmin', $aAdmin);
    }


    /**
     * 新增机构
     */
    public function addAction ()
    {
        if ($this->isPost()) {
            $aDept = $this->checkParam();
            if (empty($aDept)) {
                return;
            }
            
            $addID = Model_Tpa_Dept::addData($aDept);
            $msg   = $addID ? '新增机构成功' : '新增机构失败';
            
            return $this->showMsg($msg, true);
        } else {
            if (empty($this->aDept)) {
                return $this->redirect($this->aDeptUrl['sDeptUrl']);
            }

            $aDept['iParentID'] = $this->departmentId;
            $aDept['iAutoID'] = $this->departmentId;
            $aDept['sNumber'] = Model_Tpa_Dept::initDeptCode();
            $this->assign('aDept', $aDept);
        }
    }


    /**
     * 编辑机构信息
     * @return [array]
     */
    public function editAction ()
    {
        if ($this->isPost()) {	
            $aDept = $this->checkParam(2);
            if (empty($aDept)) {
                return;
            }
            
            $update = Model_Tpa_Dept::updData($aDept);
            $msg    = $update ? '修改成功' : '修改失败';			

            return $this->showMsg($msg, true);
        }
        
        $this->assign('aDept', $this->aDept);
    }


    /**
     * 检测参数
     * @param  [int] type (1＝新增,2=修改)
     * @return [array]
     */
    private function checkParam($type = 1)
    {
        $aParams = $this->getParams();

        if ($type == 2) {
            if (!isset($aParams['iDeptID']) || !intval($aParams['iDeptID'])) {
                return $this->showMsg('机构ID'.self::ERROR, false);
            } else {
                $aParams['iAutoID'] = $aParams['iDeptID'];
            }
        }
        
        if (empty($aParams['iParentID'])) {
            return $this->showMsg('上级机构'.self::ERROR, false);
        }        
        if (empty($aParams['sNumber'])) {
            return $this->showMsg('机构编号'.self::ERROR, false);
        }        
        if (empty($aParams['sDeptName'])) {
            return $this->showMsg('机构名称'.self::ERROR, false);
        }

        return $aParams;
    }

}