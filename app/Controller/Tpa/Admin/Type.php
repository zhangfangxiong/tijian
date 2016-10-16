<?php

class Controller_Tpa_Admin_Type extends Controller_Tpa_Admin_Base
{

    public function autoAction ()
    {
        $sClass = $this->getParam('class');
        $sKey = $this->getParam('key');
        $aList = Model_Tpa_Type::getAutocomplete($sClass, $sKey);
        return $this->showMsg($aList, true);
    }

    /**
     * (non-PHPdoc)
     *
     * @see Yaf_Controller::__call()
     */
    public function __call ($sMethod, $aArg)
    {
        $sClass = str_replace('Action', '', $sMethod);
//        print_r($sClass);die;
        $aClass = Model_Tpa_Type::getClass($sClass);
        if (empty($aClass)) {
            parent::__call($sMethod, $aArg);
            return false;
        }
        $this->listAction($sClass);
        $this->setViewScript('/Admin/Type/list.phtml');
    }

    /**
     * 删除菜单
     *
     * @return boolean
     */
    public function delAction ()
    {
        $iTypeID = $this->getParam('id');
        $aType = Model_Tpa_Type::getDetail($iTypeID);
        $sClassName = Model_Tpa_Type::getClass($aType['sClass'], 'title');
        $iRet = Model_Tpa_Type::delData($iTypeID);
        if ($iRet == 1) {
            return $this->showMsg($sClassName . '删除成功！', true);
        } else {
            return $this->showMsg($sClassName . '删除失败！', false);
        }
    }

    /**
     * 菜单列表
     */
    public function listAction ($sClass = '')
    {
        if (empty($sClass)) {
            $sClass = $this->getParam('class', '');
        }
        
        $aClass = Model_Tpa_Type::getClass($sClass);
        $aTree = Model_Tpa_Type::getTree($sClass);
        $this->assign('aTree', $aTree);
        $this->assign('aClass', $aClass);
        $this->assign('sClass', $sClass);
        $this->assign('aColor', array(
            '',
            'success',
            'warning',
            'danger',
            'info',
            'active'
        ));
    }

    /**
     * 编辑菜单
     */
    public function editAction ()
    {
        if ($this->isPost()) {

            $aType = $this->_checkData('update');
            if (empty($aType)) {
                return null;
            }
            
            $aClass = Model_Tpa_Type::getClass($aType['sClass']);
            $sClassName = $aClass['sTitle'];
            $aType['iTypeID'] = intval($this->getParam('iTypeID'));
            $aOldType = Model_Tpa_Type::getDetail($aType['iTypeID']);
            if (empty($aOldType)) {
                return $this->showMsg($sClassName . '不存在！', false);
            }

            // 更新排序，加在最后面
            if ($aOldType['iParentID'] != $aType['iParentID']) {
                $aType['iOrder'] = Model_Tpa_Type::getNextOrder($aType['iParentID']);
            }
            if (1 == Model_Tpa_Type::updData($aType)) {
                return $this->showMsg($sClassName . '信息更新成功！', true);
            } else {
                return $this->showMsg($sClassName . '信息更新失败！', false);
            }
        } else {
            $iTypeID = intval($this->getParam('id'));
            $aType = Model_Tpa_Type::getDetail($iTypeID);
            $sClass = $aType['sClass'];
            $aClass = Model_Tpa_Type::getClass($sClass);
            $aTree = Model_Tpa_Type::getTypes($sClass);
            $this->assign('aTree', $aTree);
            $this->assign('aType', $aType);
            $this->assign('aClass', $aClass);
            $this->assign('sClass', $sClass);
        }
    }

    /**
     * 增加菜单
     */
    public function addAction ()
    {
        if ($this->isPost()) {
            $aType = $this->_checkData();
            if (empty($aType)) {
                return null;
            }
            
            $aClass = Model_Tpa_Type::getClass($aType['sClass']);
            $sClassName = $aClass['sTitle'];
            $aType['iOrder'] = Model_Tpa_Type::getNextOrder($aType['iParentID']);
            if (Model_Tpa_Type::addData($aType) > 0) {
                return $this->showMsg($sClassName . '增加成功！', true);
            } else {
                return $this->showMsg($sClassName . '增加失败！', false);
            }
        } else {
            $sClass = $this->getParam('class', '');
            $aClass = Model_Tpa_Type::getClass($sClass);            

            $aTree = Model_Tpa_Type::getTypes($sClass);
            $this->assign('aTree', $aTree);
            $this->assign('aClass', $aClass);
            $this->assign('sClass', $sClass);
        }
    }

    /**
     * 菜单移动
     *
     * @return boolean
     */
    public function moveAction ()
    {
        $iTypeID = $this->getParam('id');
        $iDirect = $this->getParam('direct');
        $aType = Model_Tpa_Type::getDetail($iTypeID);
        $iCnt = Model_Tpa_Type::changeOrder($aType, $iDirect);
        return $this->showMsg($iCnt, true);
    }

    /**
     * 请求数据检测
     *
     * @return mixed
     */
    public function _checkData ($sType = 'add')
    {
        $sFullName = $this->getParam('sFullName');
        $sTypeName = $this->getParam('sTypeName');
        $sClass = $this->getParam('sClass', '');
        $iParentID = $this->getParam('iParentID');
        $sRemark = $this->getParam('sRemark');
        $sCode = $this->getParam('sCode');

        $aClass = Model_Tpa_Type::getClass($sClass);
        $sClassName = $aClass['sTitle'];
        if (! Util_Validate::isLength($sTypeName, 2, 50)) {
            return $this->showMsg($sClassName . '名称长度范围为2到50个字！', false);
        }
        $aRow = array(
            'sFullName' => $sFullName,
            'sTypeName' => $sTypeName,
            'sClass' => $aClass['sClass'],
            'iParentID' => $iParentID,
            'sRemark' => $sRemark,
            'sCode' => $sCode
        );
        if ($aClass['sImage'] != '') {
            $aRow['sImage'] = $this->getParam('sImage');
        }
        
        return $aRow;
    }
}