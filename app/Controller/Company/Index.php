<?php

/**
 * 企业后台
 * User: xuchuyuan
 * Date: 16/4/8 15:00
 */
class Controller_Company_Index extends Controller_Company_Base
{

    public function actionBefore()
    {
        parent::actionBefore();
    }



    /**
     * 首页
     */
    public function indexAction ()
    {

        //产品购买情况
        
        //公司信息
        $aCompany = Model_User::getDetail($this->enterpriseId);
        $this->assign('aCompany', $aCompany);
        
        //数据汇总
        $aStatistics = [];
        $aStatistics['sEmployeeNumber'] = Model_CustomerNew::getCnt(['where' => ['iCreateUserID' => $this->enterpriseId, 'iStatus' => 1]]);
        $aStatistics['sMonthlyNumber'] = $this->getMonthly();
        $aStatistics['sSeriousNumber'] = $this->getSerious();
        $this->assign('aStatistics', $aStatistics);        
    }    

    /**
     * 月订单汇总
     * @return [type] [description]
     */
    public function getMonthly ()
    {
        $params = $this->getParams();
        $date = date('Y-m-d', time());
        list($firstday, $lastday) = $this->getMonth($date);
        
        if (!$params['sStartDate']) {
            $params['sStartDate'] = $firstday;
        }
        if (!$params['sEndDate']) {
            $params['sEndDate'] = $lastday;
        }

        $where['iStatus IN'] = [1, 3];
        $where['iReserveTime >='] = strtotime($params['sStartDate']);
        $where['iReserveTime <='] = strtotime($params['sEndDate']);
        isset($params['iStatus']) && ($params['iStatus'] != -1) ? $where['iBookStatus'] = $params['iStatus']
        : '';

        $aEmployees = Model_Company_Company::getAll([
            'where' => [
                'iCreateUserID' => $this->enterpriseId,
                'iStatus >' => Model_Company_Company::STATUS_INVALID
            ]
        ]);

        if (!$aEmployees) {
            return 0;
        }

        $aUserIDs = [];
        foreach ($aEmployees as $key => $value) {
            if ($value['iUserID']) {
                $aUserIDs[] = $value['iUserID'];    
            }           
        }

        if (!$aUserIDs) {
            return  0;
        }

        $sUserIDs = implode(',', $aUserIDs);
        $where1['iUserID IN'] = $sUserIDs;
        
        $aCard = Model_OrderCard::getAll(['where' => $where1]);
        if ($aCard) {
            $aCardID = [];
            foreach ($aCard as $key => $value) {
                $aCardID[] = $value['iAutoID'];
            }
            $sCardID = implode(',', $aCardID);
        }

        if (!$sCardID) {
            return 0;
        }

        $where['iCardID IN'] = $sCardID;
        return Model_OrderCardProduct::getCnt(['where' => $where]);
    }


    /** 大病检出预警人数 */
    public function getSerious ()
    {
        $aEmployees = Model_Company_Company::getAll([
            'where' => [
                'iCreateUserID' => $this->enterpriseId,
                'iStatus >' => Model_Company_Company::STATUS_INVALID
            ]
        ]);

        if (!$aEmployees) {
            return 0;
        }

        $aUserIDs = [];
        foreach ($aEmployees as $key => $value) {
            if ($value['iUserID']) {
                $aUserIDs[] = $value['iUserID'];    
            }           
        }

        if (!$aUserIDs) {
            return  0;
        }

        $sUserIDs = implode(',', $aUserIDs);
        $where1['iUserID IN'] = $sUserIDs;
        
        $aCard = Model_OrderCard::getAll(['where' => $where1]);
        if ($aCard) {
            $aCardID = [];
            foreach ($aCard as $key => $value) {
                $aCardID[] = $value['iAutoID'];
            }
            $sCardID = implode(',', $aCardID);
        }

        if (!$sCardID) {
            return 0;
        }

        $where['iIsSerious'] = 1;
        $where['iCardID IN'] = $sCardID;
        return  Model_OrderCardProduct::getCnt(['where' => $where]);
    }
    
}