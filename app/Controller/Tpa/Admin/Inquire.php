<?php

/**
 * 查询中心
 * Created by PhpStorm.
 * User: Chasel
 * Date: 2016/10/10
 * Time: 9:50
 */
class Controller_Tpa_Admin_Inquire extends Controller_Tpa_Admin_Base
{
    //雇员保单列表
    public function policyAction()
    {
        $aParam = $this->getParams();
        $iPage = $this->getParam('page') ? $this->getParam('page') : 1;
        $where = [];

        if (!empty($aParam['sEmployeeRealName'])) {
            $where['sEmployeeRealName'] = $aParam['sEmployeeRealName'];
        }
        if (!empty($aParam['sEmployeeCode'])) {
            $where['sEmployeeCode'] = $aParam['sEmployeeCode'];
        }
        if (!empty($aParam['sEmployeeIdentityCard'])) {
            $where['sEmployeeIdentityCard'] = $aParam['sEmployeeIdentityCard'];
        }

        if (!empty($aParam['sCompanyName'])) {////匹配公司名称
            $cWhere = array(
                'sName' => $aParam['sCompanyName'],
                'iStatus' => 1
            );
            $company = Model_Tpa_Company::getRow(['where' => $cWhere]);
            if(!empty($company)) {
                $where['iCompanyID'] = $company['iAutoID'];
            }else{
                $where['iCompanyID'] = -99;//公司不存在
            }
        }
        if (!empty($aParam['sInsuranceNum'])) {
            $where['sInsuranceNum'] = $aParam['sInsuranceNum'];
        }
        if (!empty($aParam['sSTimeFrom'])) {
            $where['sStartDate >='] = $aParam['sSTimeFrom'];
        }
        if (!empty($aParam['sSTimeTo'])) {
            $where['sStartDate <='] = $aParam['sSTimeTo'];
        }
        if (!empty($aParam['sETimeFrom'])) {
            $where['sEndDate >='] = $aParam['sETimeFrom'];
        }
        if (!empty($aParam['sETimeTo'])) {
            $where['sEndDate <='] = $aParam['sETimeTo'];
        }

        if (!empty($aParam['iStatus'])) {
            if(1 == intval($aParam['iStatus'])) { //历史保单
                $where['sEndDate <'] = date('Y-m-d');
            }

            if(2 == intval($aParam['iStatus'])) { //当前保单
                $where['sEndDate >='] = date('Y-m-d');
            }
        }

        $aData = Model_Tpa_Claimsplan::getList($where, $iPage);
        $this->assign('aData', $aData);
        $this->assign('aParam', $aParam);
    }

    //短信列表
    public function messagesAction()
    {
        $aParam = $this->getParams();
        $iPage = $this->getParam('page') ? $this->getParam('page') : 1;
        $where = [];

        $aData = Model_Tpa_Express::getList($where, $iPage);
        $this->assign('aData', $aData);
        $this->assign('aParam', $aParam);
    }

}