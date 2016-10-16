<?php

/**
 * tpa快递收入
 * Created by PhpStorm.
 * User: zhangfangxiong
 * Date: 2016/10/08
 * Time: 9:50
 */
class Controller_Tpa_Admin_Express extends Controller_Tpa_Admin_Base
{
    //快递收入列表
    public function listAction()
    {
        $aParam = $this->getParams();
        $iType = $this->getParam('type') ? $this->getParam('type') : 1;
        $iPage = $this->getParam('page') ? $this->getParam('page') : 1;
        $where = [];
        if (in_array($iType, [1, 2])) {
            $where['iType'] = $iType;
            $where['iStatus'] = Model_Tpa_Express::NOPRINT;
        } else {
            if (!empty($aParam['sExpressCode'])) {
                $where['sExpressCode'] = $aParam['sExpressCode'];
            }
            if (!empty($aParam['iUserID'])) {
                $where['iUserID'] = $aParam['iUserID'];
            }
            if (!empty($aParam['sDate'])) {
                $sStartTime = strtotime($aParam['sDate']);
                $where['iUpdateTime >'] = $sStartTime;
                $where['iUpdateTime <'] = $sStartTime + 86400;
            }
            if (!empty($aParam['iType'])) {
                $where['iType'] = $aParam['iType'];
            }
            $where['iStatus'] = Model_Tpa_Express::HASPRINT;

        }
        $aData = Model_Tpa_Express::getList($where, $iPage,'iUpdateTime DESC');
        $this->assign('aData', $aData);
        $this->assign('aStatus', Yaf_G::getConf('aPrintStatus'));
        $this->assign('aExpressType', Yaf_G::getConf('aExpressType'));
        $this->assign('iType', $iType);
        $this->assign('aParam', $aParam);
    }

    //快递收入操作
    public function addAction()
    {
        $aParams = $this->getParams();
        if (!empty($aParams['type']) && in_array($aParams['type'], [Model_Tpa_Express::TYPESELF, Model_Tpa_Express::TYPEEXPRESS])) {
            if ($aParams['type'] == Model_Tpa_Express::TYPESELF) {
                if (empty($aParams['sExpressCode'])) {
                    return $this->showMsg('快递单号不能为空！', false);
                }
                $aExpress = Model_Tpa_Express::getExpressByCode($aParams['sExpressCode']);
                if (!empty($aExpress)) {
                    return $this->showMsg('该快递单号已经录入，请检查是否单号输入有误！', false);
                }
            } elseif ($aParams['type'] == Model_Tpa_Express::TYPEEXPRESS) {
                $aParams['sExpressCode'] = Model_Tpa_Express::initExpressCode();
            } else {
                return $this->showMsg('非法操作', false);
            }
            $aParams['iType'] = $aParams['type'];
            $aParams['iCreateUserID'] = $aParams['iUpdateUserID'] = $this->aCurrUser['iUserID'];
            if (Model_Tpa_Express::addData($aParams)) {
                return $this->showMsg('录入成功！', true);
            }
            return $this->showMsg('录入失败！', false);
        } else {
            return $this->showMsg('非法操作', false);
        }
    }

    //删除快递
    public function delAction()
    {
        $iID = $this->getParam('id') ? intval($this->getParam('id')) : 0;
        if (empty($iID)) {
            return $this->showMsg('请先选择快递单', false);
        }
        $aParam['iStatus'] = 0;
        $aParam['iAutoID'] = $iID;
        if (Model_Tpa_Express::updData($aParam)) {
            return $this->showMsg('删除成功！', true);
        } else {
            return $this->showMsg('删除失败！', false);
        }
    }

    //打印快递
    public function printAction()
    {
        $iIDs = $this->getParam('id') ? $this->getParam('id') : 0;
        if (empty($iIDs)) {
            return $this->showMsg('请先选择快递单', false);
        }
        //调用打印接口
        //todo
        //批量标记为已打印
        $iResult = Model_Tpa_Express::printExpress($iIDs,$this->aCurrUser['iUserID']);
        if ($iResult) {
            return $this->showMsg('打印接口未完成，先假装已经打印成功！', true);
        } else {
            return $this->showMsg('打印失败！', false);
        }

    }
}