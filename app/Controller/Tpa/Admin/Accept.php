<?php

/**
 * tpa理赔受理
 * Created by PhpStorm.
 * User: zhangfangxiong
 * Date: 2016/10/08
 * Time: 9:50
 */
class Controller_Tpa_Admin_Accept extends Controller_Tpa_Admin_Base
{
    //受理雇员列表
    public function employeeAction()
    {
        $aParam = $this->getParams();
        $iPage = $this->getParam('page') ? $this->getParam('page') : 1;
        $where['iStatus'] = Model_Tpa_Employee::STATUS_VALID;
        if (!empty($aParam['sCode'])) {
            $where['sCode'] = $aParam['sCode'];
        }
        if (!empty($aParam['sRealName'])) {
            $where['sRealName'] = $aParam['sRealName'];
        }
        if (!empty($aParam['sIdentityCard'])) {
            $where['sIdentityCard'] = $aParam['sIdentityCard'];
        }
        $aYesOrNo3 = Yaf_G::getConf('aYesOrNo3');
        $aCardType = Yaf_G::getConf('aCardType');
        $aEmployeeType = Yaf_G::getConf('aEmployeeType');
        $aEmployeeLevel = Yaf_G::getConf('aEmployeeLevel');


        $aData = Model_Tpa_Employee::getList($where, $iPage, 'iUpdateTime DESC');
        if (!empty($aData['aList'])) {
            foreach ($aData['aList'] as $key => &$value) {
                $aCompany = Model_Tpa_Company::getDetail($value['iCompanyID']);
                $value['sCompanyName'] = !empty($aCompany['sName']) ? $aCompany['sName'] : '';
                $value['sCardType'] = !empty($aCardType[$value['iCardType']]) ? $aCardType[$value['iCardType']] : '';
                $value['sIsMedicalInsurance'] = !empty($aYesOrNo3[$value['iIsMedicalInsurance']]) ? $aYesOrNo3[$value['iIsMedicalInsurance']] : '';
                $value['sType'] = !empty($aEmployeeType[$value['iType']]) ? $aEmployeeType[$value['iType']] : '';
                $value['sLevel'] = !empty($aEmployeeLevel[$value['iLevel']]) ? $aEmployeeLevel[$value['iLevel']] : '';
                if (!empty($value['iParentID'])) {
                    $aParent = Model_Tpa_Employee::getDetail($value['iParentID']);
                    $value['sParentName'] = !empty($aParent['sRealName']) ? $aParent['sRealName'] : '';
                } else {
                    $value['sParentName'] = $value['sRealName'];
                }
            }
        }
        $this->assign('iType', 1);
        $this->assign('aData', $aData);
        $this->assign('aParam', $aParam);
    }

    //未打印批次案件
    public function caseAction()
    {
        $aParam = $this->getParams();
        $iPage = $this->getParam('page') ? $this->getParam('page') : 1;
        $where['iStatus >'] = 0;
        if (!empty($aParam['sCode'])) {
            $where['sCode'] = $aParam['sCode'];
        }
        if (!empty($aParam['sExpressCode'])) {
            $where['sExpressCode'] = trim($aParam['sExpressCode']);
        }
        if (!empty($aParam['sEmployeeCode'])) {
            $where['sEmployeeCode'] = $aParam['sEmployeeCode'];
        }
        if (!empty($aParam['sEmployeeRealName'])) {
            $where['sEmployeeRealName'] = $aParam['sEmployeeRealName'];
        }
        if (!empty($aParam['sEmployeeIdentityCard'])) {
            $where['sEmployeeIdentityCard'] = $aParam['sEmployeeIdentityCard'];
        }
        if (!empty($aParam['sStartDate'])) {
            $where['iCreateTime >'] = strtotime($aParam['sStartDate']);
        }
        if (!empty($aParam['sEndDate'])) {
            $where['iCreateTime <'] = strtotime($aParam['sEndDate']);
        }
        if (!empty($aParam['iCreateUserID'])) {
            $where['iCreateUserID'] = $aParam['iCreateUserID'];
        }
        $where['sBatchNumber'] = 0;//未打印批次号


        $aData = Model_Tpa_Case::getList($where, $iPage, 'iUpdateTime DESC');
        if (!empty($aData['aList'])) {
            $aPriority = Yaf_G::getConf('aPriority');
            foreach ($aData['aList'] as $key => &$value) {
                $aUser = Model_Tpa_Admin::getDetail($value['iCreateUserID']);
                $value['sCreateUserID'] = !empty($aUser['sUserName']) ? $aUser['sUserName'] : '';
                $value['sPriority'] = !empty($aPriority[$value['iPriority']]) ? $aPriority[$value['iPriority']] : '';
            }
        }
        //这里先不管权限，把所有管理员列出来
        $aUserParam['iStatus >'] = 0;
        $aUser = Model_Tpa_Admin::getAll($aUserParam);
        //只展示该权限的用户
        //todo
        $this->assign('iType', 2);
        $this->assign('aData', $aData);
        $this->assign('aParam', $aParam);
        $this->assign('aUser', $aUser);
    }

    //受理雇员列表
    public function errorAction()
    {
        $aParam = $this->getParams();
        $iPage = $this->getParam('page') ? $this->getParam('page') : 1;
        $where = [];
        if (!empty($aParam['sExpressCode'])) {
            $where['sExpressCode'] = $aParam['sExpressCode'];
        }
        if (!empty($aParam['iStatus'])) {
            $where['iStatus'] = $aParam['iStatus'];
        }
        if (!empty($aParam['iUpdateTime'])) {
            $sStartTime = strtotime($aParam['iUpdateTime']);
            $where['iUpdateTime >'] = $sStartTime;
            $where['iUpdateTime <'] = $sStartTime + 86400;
        }
        $where['iStatus >'] = 0;
        $where['iCreateUserStationID'] = 1;//受理岗提交的案件
        $aData = Model_Tpa_Error::getList($where, $iPage, 'iUpdateTime DESC');
        $aErrorType = Yaf_G::getConf('aErrorType');
        $aErrorStatus = Yaf_G::getConf('aErrorStatus');
        if (!empty($aData['aList'])) {
            foreach ($aData['aList'] as $key => &$value) {
                $aAdmin = Model_Tpa_Admin::getDetail($value['iUpdateUserID']);
                $value['sUpdateUserName'] = !empty($aAdmin['sUserName']) ? $aAdmin['sUserName'] : '';
                $value['sType'] = !empty($aErrorType[$value['iType']]) ? $aErrorType[$value['iType']] : '';
                $value['sStatus'] = !empty($aErrorStatus[$value['iStatus']]) ? $aErrorStatus[$value['iStatus']] : '';
            }
        }
        $this->assign('iType', 3);
        $this->assign('aStatus', $aErrorStatus);
        $this->assign('aData', $aData);
        $this->assign('aParam', $aParam);
    }

    //快递收入操作
    public function addErrorAction()
    {
        $sUrl = '';
        $aParams = $this->getParams();
        if (empty($aParams['sExpressCode'])) {
            return $this->showMsg('快递单号不能为空', false);
        }
        if (empty($aParams['iInvoiceNum'])) {
            return $this->showMsg('发票数目不能为空', false);
        }
        if (empty($aParams['iType'])) {
            return $this->showMsg('请选择问题类型 ', false);
        }
        if (empty($aParams['iID'])) {
            if (empty($aParams['sEmployeeRealName'])) {
                return $this->showMsg('姓名不能为空', false);
            }
            if (empty($aParams['sMobile'])) {
                return $this->showMsg('手机不能为空', false);
            }
            if (empty($aParams['sEmployeeIdentityCard'])) {
                return $this->showMsg('身份证不能为空', false);
            }
        } else {
            if (empty($aParams['iMoney'])) {
                return $this->showMsg('受理金额不能为空', false);
            }
            $aEmployee = Model_Tpa_Employee::getDetail($aParams['iID']);
            $aParams['iEmployeeID'] = $aEmployee['iAutoID'];
            $aParams['sEmployeeCode'] = $aEmployee['sCode'];
            $aParams['sEmployeeRealName'] = $aEmployee['sRealName'];
            $aParams['sMobile'] = $aEmployee['sMobile'];
            $aParams['sEmployeeIdentityCard'] = $aEmployee['sIdentityCard'];
            $sUrl = '/tpa/admin/accept/error/';
        }
        $aParams['iCreateUserID'] = $aParams['iUpdateUserID'] = $this->aCurrUser['iUserID'];
        $aParams['sCode'] = Model_Tpa_Error::initCode();
        if (Model_Tpa_Error::addData($aParams)) {
            return $this->showMsg('提交问题岗成功！', true, $sUrl);
        }
        return $this->showMsg('提交问题岗失败！', false);
    }

    //受理案件
    public function addCaseAction()
    {
        $iID = $this->getParam('id') ? intval($this->getParam('id')) : 0;
        $iErrorid = $this->getParam('errorid') ? intval($this->getParam('errorid')) : 0;
        if (empty($iID) && empty($iErrorid)) {
            return $this->showMsg('非法操作', false);
        } else {
            if (!empty($iID)) {
                $aEmployee = Model_Tpa_Employee::getDetail($iID);
                if (empty($aEmployee)) {
                    return $this->showMsg('雇员不存在', false);
                }
            } elseif (!empty($iErrorid)) {
                $aError = Model_Tpa_Error::getDetail($iErrorid);
                if (empty($aError)) {
                    return $this->showMsg('问题件不存在', false);
                }
                $aEmployee = Model_Tpa_Employee::getEmployeeByIDAndName($aError['sIdentityCard'],$aError['sRealName']);
                if (empty($aEmployee)) {
                    return $this->show404('雇员不存在,请联系问题岗！', false);
                }
            }
        }


        if ($this->isPost()) {
            $aParam = $this->getParams();
            $aParam['sCode'] = Model_Tpa_Case::initCode();
            $aParam['sEmployeeID'] = $aEmployee['iAutoID'];
            $aParam['sEmployeeCode'] = $aEmployee['sCode'];
            $aParam['sEmployeeRealName'] = $aEmployee['sRealName'];
            $aParam['sEmployeeIdentityCard'] = $aEmployee['sIdentityCard'];
            $aParam['sEmployeeCompanyName'] = $aEmployee['iAutoID'];
            $aParam['sEmployeeCompanyID'] = $aEmployee['iCompanyID'];
            $aParam['iCreateUserID'] = $aParam['iUpdateUserID'] = $this->aCurrUser['iUserID'];
            if (Model_Tpa_Case::addData($aParam)) {
                return $this->showMsg('受理成功', true, '/tpa/admin/accept/case/');
            } else {
                return $this->showMsg('受理失败', false);
            }
        }

        $aYesOrNo2 = Yaf_G::getConf('aYesOrNo2');
        $aPriority = Yaf_G::getConf('aPriority');
        $aSex = Yaf_G::getConf('aSex');
        $aCardType = Yaf_G::getConf('aCardType');
        $aYesOrNo3 = Yaf_G::getConf('aYesOrNo3');
        $aEmployeeType = Yaf_G::getConf('aEmployeeType');
        $aEmployeeLevel = Yaf_G::getConf('aEmployeeLevel');
        $aErrorType = Yaf_G::getConf('aErrorType');
        $aErrorStatus = Yaf_G::getConf('aErrorStatus');


        $aEmployee['sSex'] = !empty($aSex[$aEmployee['iSex']]) ? $aSex[$aEmployee['iSex']] : '';
        $aEmployee['sCardType'] = !empty($aCardType[$aEmployee['iCardType']]) ? $aCardType[$aEmployee['iCardType']] : '';
        $aEmployee['sIsMedicalInsurance'] = !empty($aYesOrNo3[$aEmployee['iIsMedicalInsurance']]) ? $aYesOrNo3[$aEmployee['iIsMedicalInsurance']] : '';
        $aEmployee['sType'] = !empty($aEmployeeType[$aEmployee['iType']]) ? $aEmployeeType[$aEmployee['iType']] : '';
        $aEmployee['sLevel'] = !empty($aEmployeeLevel[$aEmployee['iLevel']]) ? $aEmployeeLevel[$aEmployee['iLevel']] : '';

        $aMedicalInsurancePrivinceID = Model_Tpa_Region::getDetail($aEmployee['iMedicalInsurancePrivinceID']);
        $aMedicalInsuranceCityID = Model_Tpa_Region::getDetail($aEmployee['iMedicalInsuranceCityID']);
        $aJobPrivinceID = Model_Tpa_Region::getDetail($aEmployee['iJobPrivinceID']);
        $aJobCityID = Model_Tpa_Region::getDetail($aEmployee['iJobCityID']);
        $aCompany = Model_Tpa_Company::getDetail($aEmployee['iCompanyID']);
        $aEmployee['sMedicalInsurancePrivinceID'] = !empty($aMedicalInsurancePrivinceID['region_name']) ? $aMedicalInsurancePrivinceID['region_name'] : '';
        $aEmployee['sMedicalInsuranceCityID'] = !empty($aMedicalInsuranceCityID['region_name']) ? $aMedicalInsuranceCityID['region_name'] : '';
        $aEmployee['sJobPrivinceID'] = !empty($aJobPrivinceID['region_name']) ? $aJobPrivinceID['region_name'] : '';
        $aEmployee['sJobCityID'] = !empty($aJobCityID['region_name']) ? $aJobCityID['region_name'] : '';
        $aEmployee['sCompanyName'] = !empty($aCompany['sName']) ? $aCompany['sName'] : '';
        $aEmployee['sCompanyCode'] = !empty($aCompany['sCode']) ? $aCompany['sCode'] : '';

        //雇员历史投保记录
        $aClaimsplanParam['sEmployeeID'] = $aEmployee['iAutoID'];
        $aClaimsplanParam['iStatus >'] = 0;
        $aClaimsplan = Model_Tpa_Claimsplan::getAll($aClaimsplanParam);
        if (!empty($aClaimsplan)) {
            foreach ($aClaimsplan as $key => &$value) {
                $aUser = Model_Tpa_Admin::getDetail($value['iCreateUserID']);
                if (!empty($aUser)) {
                    $value['sCreateUserID'] = $aUser['sUserName'];
                }
            }
        }
        //雇员历史受理记录
        $aCaseParam['sEmployeeID'] = $aEmployee['iAutoID'];
        $aCaseParam['iStatus >'] = 0;
        $aCase = Model_Tpa_Error::getAll($aCaseParam);
        if (!empty($aCase)) {
            foreach ($aCase as $key => &$value) {
                $aUser = Model_Tpa_Admin::getDetail($value['iCreateUserID']);
                if (!empty($aUser)) {
                    $value['sCreateUserID'] = $aUser['sUserName'];
                }
            }
        }
        $this->assign('iID', $iID);
        $this->assign('aYesOrNo2', $aYesOrNo2);
        $this->assign('aPriority', $aPriority);
        $this->assign('aEmployee', $aEmployee);
        $this->assign('aCase', $aCase);
        $this->assign('aClaimsplan', $aClaimsplan);
        $this->assign('aErrorType', $aErrorType);
        $this->assign('aErrorStatus', $aErrorStatus);
        $this->assign('iOperationType', 1);
    }

    //查看案件
    public function viewCaseAction()
    {
        $iID = $this->getParam('id') ? intval($this->getParam('id')) : 0;
        if (empty($iID)) {
            return $this->showMsg('非法操作', false);
        }
        $aThisCase = Model_Tpa_Case::getDetail($iID);
        if (empty($aThisCase)) {
            return $this->showMsg('案件不存在', false);
        }
        $aEmployee = Model_Tpa_Employee::getDetail($aThisCase['sEmployeeID']);
        if (empty($aEmployee)) {
            return $this->showMsg('雇员不存在', false);
        }

        $aYesOrNo2 = Yaf_G::getConf('aYesOrNo2');
        $aPriority = Yaf_G::getConf('aPriority');
        $aSex = Yaf_G::getConf('aSex');
        $aCardType = Yaf_G::getConf('aCardType');
        $aYesOrNo3 = Yaf_G::getConf('aYesOrNo3');
        $aEmployeeType = Yaf_G::getConf('aEmployeeType');
        $aEmployeeLevel = Yaf_G::getConf('aEmployeeLevel');
        $aErrorType = Yaf_G::getConf('aErrorType');
        $aErrorStatus = Yaf_G::getConf('aErrorStatus');


        $aEmployee['sSex'] = !empty($aSex[$aEmployee['iSex']]) ? $aSex[$aEmployee['iSex']] : '';
        $aEmployee['sCardType'] = !empty($aCardType[$aEmployee['iCardType']]) ? $aCardType[$aEmployee['iCardType']] : '';
        $aEmployee['sIsMedicalInsurance'] = !empty($aYesOrNo3[$aEmployee['iIsMedicalInsurance']]) ? $aYesOrNo3[$aEmployee['iIsMedicalInsurance']] : '';
        $aEmployee['sType'] = !empty($aEmployeeType[$aEmployee['iType']]) ? $aEmployeeType[$aEmployee['iType']] : '';
        $aEmployee['sLevel'] = !empty($aEmployeeLevel[$aEmployee['iLevel']]) ? $aEmployeeLevel[$aEmployee['iLevel']] : '';

        $aMedicalInsurancePrivinceID = Model_Tpa_Region::getDetail($aEmployee['iMedicalInsurancePrivinceID']);
        $aMedicalInsuranceCityID = Model_Tpa_Region::getDetail($aEmployee['iMedicalInsuranceCityID']);
        $aJobPrivinceID = Model_Tpa_Region::getDetail($aEmployee['iJobPrivinceID']);
        $aJobCityID = Model_Tpa_Region::getDetail($aEmployee['iJobCityID']);
        $aCompany = Model_Tpa_Company::getDetail($aEmployee['iCompanyID']);
        $aEmployee['sMedicalInsurancePrivinceID'] = !empty($aMedicalInsurancePrivinceID['region_name']) ? $aMedicalInsurancePrivinceID['region_name'] : '';
        $aEmployee['sMedicalInsuranceCityID'] = !empty($aMedicalInsuranceCityID['region_name']) ? $aMedicalInsuranceCityID['region_name'] : '';
        $aEmployee['sJobPrivinceID'] = !empty($aJobPrivinceID['region_name']) ? $aJobPrivinceID['region_name'] : '';
        $aEmployee['sJobCityID'] = !empty($aJobCityID['region_name']) ? $aJobCityID['region_name'] : '';
        $aEmployee['sCompanyName'] = !empty($aCompany['sName']) ? $aCompany['sName'] : '';
        $aEmployee['sCompanyCode'] = !empty($aCompany['sCode']) ? $aCompany['sCode'] : '';

        //雇员历史投保记录
        $aClaimsplanParam['sEmployeeID'] = $aEmployee['iAutoID'];
        $aClaimsplanParam['iStatus >'] = 0;
        $aClaimsplan = Model_Tpa_Claimsplan::getAll($aClaimsplanParam);
        if (!empty($aClaimsplan)) {
            foreach ($aClaimsplan as $key => &$value) {
                $aUser = Model_Tpa_Admin::getDetail($value['iCreateUserID']);
                if (!empty($aUser)) {
                    $value['sCreateUserID'] = $aUser['sUserName'];
                }
            }
        }
        //雇员历史受理记录
        $aCaseParam['sEmployeeID'] = $aEmployee['iAutoID'];
        $aCaseParam['iStatus >'] = 0;
        $aCase = Model_Tpa_Error::getAll($aCaseParam);
        if (!empty($aCase)) {
            foreach ($aCase as $key => &$value) {
                $aUser = Model_Tpa_Admin::getDetail($value['iCreateUserID']);
                if (!empty($aUser)) {
                    $value['sCreateUserID'] = $aUser['sUserName'];
                }
            }
        }
        $this->assign('iID', $iID);
        $this->assign('aYesOrNo2', $aYesOrNo2);
        $this->assign('aPriority', $aPriority);
        $this->assign('aEmployee', $aEmployee);
        $this->assign('aCase', $aCase);
        $this->assign('aThisCase', $aThisCase);
        $this->assign('aClaimsplan', $aClaimsplan);
        $this->assign('aErrorType', $aErrorType);
        $this->assign('aErrorStatus', $aErrorStatus);
        $this->assign('iOperationType', 2);
    }

    //打印批次号
    public function printAction()
    {
        $iIDs = $this->getParam('id') ? $this->getParam('id') : 0;
        if (empty($iIDs)) {
            return $this->showMsg('请先选择案件', false);
        }
        //调用打印接口
        //todo
        //批量标记为已打印
        $iResult = Model_Tpa_Case::printCase($iIDs, $this->aCurrUser['iUserID']);
        if ($iResult) {
            return $this->showMsg('打印接口未完成，先假装已经打印成功！', true);
        } else {
            return $this->showMsg('打印失败！', false);
        }

    }

}