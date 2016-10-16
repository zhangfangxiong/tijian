<?php

class Model_OrderCard extends Model_Base
{

    const TABLE_NAME = 't_order_card_new';
    const ORDERTYPE_PCARD = 1;//电子卡
    const ORDERTYPE_RCARD = 2;//实体卡
    const ORDERTYPE_PRODUCT = 3;//体检产品
    const ORDERTYPE_PRODUCT_PLAN = 4;//体检计划
    const CARDTYPE_ANXIN = 1;//安心体检卡
    const CARDTYPE_ZHONGZHI = 2;//中智体检卡
    const USETYPE_OR = 1;//OR
    const USETYPE_AND = 2;//AND
    const PAYTYPE_PERSON = 1;//个人支付
    const PAYTYPE_COMPANY = 2;//公司支付
    const PAYSTATUS_0 = 0;//待支付
    const PAYSTATUS_1 = 1;//已支付

    public static $aStatus = [
        '0' => '未预约',
        '1' => '已预约',
        '2' => '已到检',
        '3' => '已退订',
        '4' => '作废',
        '5' => '已出报告',
        '6' => '预约失败'
    ];

    /**
     *
     * @param unknown $aParam
     */
    public static function getStat2($aParam)
    {
        $aWhere = array('p.iStatus>0');
        if (!empty($aParam['sStartDate'])) {
            $aWhere[] = 'p.sStartDate>="' . $aParam['sStartDate'] . '"';
        }
        if (!empty($aParam['sEndDate'])) {
            $aWhere[] = 'p.sStartDate<="' . $aParam['sEndDate'] . '"';
        }

        $sWhere = ' WHERE ' . join(' AND ', $aWhere);
        $sSQL = 'SELECT c.sRealName as sCoName, COUNT(*) iUserCnt, SUM(p.sCost) sCost FROM ' . self::TABLE_NAME . ' AS p
    			 LEFT JOIN t_user AS c ON p.iHRID=c.iUserID
    			' . $sWhere . ' GROUP BY iHRID';
        return self::query($sSQL);
    }


    /**
     * 供应商数据汇总
     * @param unknown $aParam
     */
    public static function getStat3($aParam)
    {
        $aWhere = array('p.iStatus>0');
        if (!empty($aParam['sStartDate'])) {
            $aWhere[] = 'p.iOrderTime>="' . strtotime($aParam['sStartDate']) . '"';
        }
        if (!empty($aParam['sEndDate'])) {
            $aWhere[] = 'p.iOrderTime<="' . strtotime($aParam['sEndDate']) . '"';
        }

        $sWhere = ' WHERE ' . join(' AND ', $aWhere);
        $sSQL = 'SELECT s.iSupplierID, t.sTypeName as sSupplierName, COUNT(*) iUserCnt, SUM(p.sCost) sCost FROM ' . self::TABLE_NAME . ' AS p
    			 LEFT JOIN t_store AS s ON p.iStoreID=s.iStoreID
    		     LEFT JOIN t_type AS t ON t.iTypeID=s.iSupplierID
    			' . $sWhere . ' GROUP BY s.iSupplierID';
        $aList = self::query($sSQL);

        $sWhere .= ' AND p.iBalance=1';
        $sSQL = 'SELECT s.iSupplierID, SUM(p.sCost) sCost FROM ' . self::TABLE_NAME . ' AS p
    			 LEFT JOIN t_store AS s ON p.iStoreID=s.iStoreID
    			' . $sWhere . ' GROUP BY s.iSupplierID';
        $aBalance = self::query($sSQL, 'pair');
        foreach ($aList as $k => $v) {
            $aList[$k]['bCost'] = isset($aBalance[$v['iSupplierID']]) ? $aBalance[$v['iSupplierID']] : 0;
        }

        return $aList;
    }

    /**
     * 供应商数据汇总
     * @param unknown $aParam
     */
    public static function getStat4($aParam)
    {
        $aWhere = array('p.iStatus>0');
        if (!empty($aParam['sStartDate'])) {
            $aWhere[] = 'p.iOrderTime>="' . strtotime($aParam['sStartDate']) . '"';
        }
        if (!empty($aParam['sEndDate'])) {
            $aWhere[] = 'p.iOrderTime<="' . strtotime($aParam['sEndDate']) . '"';
        }

        $sWhere = ' WHERE ' . join(' AND ', $aWhere);
        $sSQL = 'SELECT p.iStoreID, s.sName as sStoreName, COUNT(*) iUserCnt, SUM(p.sCost) sCost FROM ' . self::TABLE_NAME . ' AS p
    			 LEFT JOIN t_store AS s ON p.iStoreID=s.iStoreID
    			' . $sWhere . ' GROUP BY p.iStoreID';
        $aList = self::query($sSQL);

        $sWhere .= ' AND p.iBalance=1';
        $sSQL = 'SELECT p.iStoreID, SUM(p.sCost) sCost FROM ' . self::TABLE_NAME . ' AS p
    			' . $sWhere . ' GROUP BY p.iStoreID';
        $aBalance = self::query($sSQL, 'pair');
        foreach ($aList as $k => $v) {
            $aList[$k]['bCost'] = isset($aBalance[$v['iStoreID']]) ? $aBalance[$v['iStoreID']] : 0;
        }

        return $aList;
    }



    /**
     * 构建搜索条件
     * @param unknown $aParam
     */
    public static function _buildWhere($aParam)
    {
        $aWhere = array();
        if (isset($aParam['iAutoID']) && !empty($aParam['iAutoID'])) {
            $aWhere[] = 'p.iAutoID=' . $aParam['iAutoID'];
        }
        if (isset($aParam['iAutoID NOT IN']) && !empty($aParam['iAutoID NOT IN'])) {
            $iAutoID = implode(',', $aParam['iAutoID NOT IN']);
            $aWhere[] = 'p.iAutoID NOT IN (' . $iAutoID . ')';
        }
        if (!empty($aParam['iUserID'])) {
            $aWhere[] = 'p.iUserID=' . intval($aParam['iUserID']) . '';
        }

        if (!empty($aParam['sRealName'])) {
            $aWhere[] = 'c.sRealName="' . addslashes($aParam['sRealName']) . '"';
        }
		if (! empty($aParam['sUserName'])) {
			$aWhere[] = 'c.sUserName="' . addslashes($aParam['sUserName']) . '"';
		}
		//公司编号
		if (! empty($aParam['sCoCode'])) {
			$where = array('sCompanyCode' => addslashes($aParam['sCoCode']));
			$userIDs = Model_CustomerCompany::getCol(['where' => $where, 'group' => 'iUserID'], 'iUserID');

			if($userIDs) {
				$aWhere[] = 'p.iUserID in (' . implode(",", $userIDs) . ')';
			}else {//查不到情况
				$aWhere[] = 'p.iUserID = -99';
			}
		}
		//公司名称
		if (! empty($aParam['sCoName'])) {
			$where = array('sCompanyName' => addslashes($aParam['sCoCode']));
			$userIDs = Model_CustomerCompany::getCol(['where' => $where, 'group' => 'iUserID'], 'iUserID');
			if($userIDs) {
				$aWhere[] = 'p.iUserID in (' . implode(",", $userIDs) . ')';
			}else {//查不到情况
				$aWhere[] = 'p.iUserID = -99';
			}
		}


		if (isset($aParam['iBookStatus']) && -1 != intval($aParam['iBookStatus'])) {
			$aWhere[] = 'p.iBookStatus=' . intval($aParam['iBookStatus']) . '';
		}
        if (! empty($aParam['iPreStatus'])) {
            $aWhere[] = 'p.iPreStatus=' . intval($aParam['iPreStatus']) . '';
        }
        if (isset($aParam['iStatus']) && -1 != intval($aParam['iStatus'])) {
            $aWhere[] = 'p.iStatus=' . intval($aParam['iStatus']) . '';
        }
        if (!empty($aParam['iPhysicalType'])) {
            $aWhere[] = 'p.iPhysicalType=' . intval($aParam['iPhysicalType']) . '';
        }
        if (! empty($aParam['iPhysicalTime'])) {
			$iPhysicalTime = strtotime($aParam['iPhysicalTime']);
			$sPhysicalTime = date("Y-m-d", $iPhysicalTime);

            $aWhere[] = 'p.iOrderTime >=' . strtotime($sPhysicalTime. "00:00:00") . '';
			$aWhere[] = 'p.iOrderTime <=' . strtotime($sPhysicalTime. "23:59:59") . '';
        }
		if (! empty($aParam['iCityID'])) {
			$aWhere[] = 's.iCityID=' . intval($aParam['iCityID']) . '';
		}
		if (! empty($aParam['iPlat'])) {
			$aWhere[] = 'p.iPlat=' . intval($aParam['iPlat']) . '';
		}

		if (-1 != intval($aParam['iSendMsg'])) {
			$aWhere[] = 'p.iSendMsg=' . intval($aParam['iSendMsg']) . '';
		}
		if (-1 != intval($aParam['iSendEMail'])) {
			$aWhere[] = 'p.iSendEMail=' . intval($aParam['iSendEMail']) . '';
		}
		if (! empty($aParam['sIdentityCard'])) {
			$aWhere[] = 'c.sIdentityCard= "' . addslashes($aParam['sIdentityCard']) . '"';
		}
		if (! empty($aParam['sCardCode'])) {
			$aWhere[] = 'p.sCardCode= "' . addslashes($aParam['sCardCode']) . '"';
		}

		//操作时间
		if (! empty($aParam['sOptStartDate'])) {
			$aWhere[] = 'p.iCreateTime >=' . strtotime($aParam['sOptStartDate']) . '';
		}
		if (! empty($aParam['sOptEndDate'])) {
			$aWhere[] = 'p.iCreateTime <=' . strtotime($aParam['sOptEndDate']) . '';
		}

		//是否显示体检卡
		if(!empty($aParam['iIsShow'])) {
			$aWhere[] = "p.iCardType in (4, 5)";
		}

        if (empty($aWhere)) {
            $sWhere = '';
        } else {
            $sWhere = ' WHERE ' . join(' AND ', $aWhere);
        }

        return $sWhere;
    }

    /**
     * 规则：实体卡9开头，电子卡6开头,实体卡按900,901区分基础卡，经典卡等，第四位是区分安心和中智，后面预留一位0，后面随机15位
     * 生成卡号（自动生成的）
     * @param int $iType 1=电子卡，2=实体卡,3=体检产品，4=体检计划
     * @param int $iPCard 卡种类(0=未知，1=基础卡，2=经典卡，3=精英卡。。。在配置文件里，同产品设置中的实体卡类型)
     * @param int $iCardType 1=安心卡，2=中智卡
     * @return string
     */
    public static function initCardCode($iType = 1,$iPCard=1,$iCardType=1)
    {
        if ($iType == 2) {
            $sPrefix = '9';
        } else {
            $sPrefix = '6';
            $iCardType = 0;
            $iPCard= 0;
        }
        $aPCardPrefix = Yaf_G::getConf('aPCardPrefix','product');
        $sPrefix .= $aPCardPrefix[$iPCard];
        $sPrefix .= $iCardType;
        $sPrefix .= '0';//预留一位0
        $sProductCode = $sPrefix . Util_Tools::passwdGen(15, 1);
        if (self::getCardByCode($sProductCode)) {
            self::initCardCode($iType,$iPCard,$iCardType);
        }
        return $sProductCode;
    }

    //生成序列号
    public static function initCardSerial()
    {
        $sPrefix = 'sn';
        $sProductCode = $sPrefix . Util_Tools::passwdGen(8, 1);
        if (self::getCardBySerialCode($sProductCode)) {
            self::initCardSerial();
        }
        return $sProductCode;
    }

    public static function getCardByCode($sCode, $iStatus = null)
    {
        $aWhere = array(
            'sCardCode' => $sCode,
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

    //获取序列号
    public static function getCardBySerialCode($sCode, $iStatus = null)
    {
        $aWhere = array(
            'sSerialCode' => $sCode,
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

    /**
     * 获取用户的体检卡（已付款的体检卡）
     */
    public static function getUserCardList($iUserID,$aPage)
    {
        $aWhere = array(
            'iUserID' => $iUserID,
            'iStatus' => 1,//有效
            'iBindStatus' => 1,//已绑定
            //'sWhere' => '(iPayType=1 OR (iPayType=2 AND iPayStatus=1))'
        );
        return self::getList($aWhere, $aPage, 'iCreateTime DESC',20);
    }

    //根据体检卡获取用户
    public static function getCardByMedicalCard($sMedicalCard)
    {
        $aWhere = array(
            'sCardCode' => $sMedicalCard
        );
        return self::getRow(array(
            'where' => $aWhere
        ));
    }


    /**
     * 根据卡号获取订单id,体检记录ID
     * @param  $sMedicalCard
     * @return
     */
    public static function getOrderIDByCard($sCardCode)
    {
        $aCard = self::getRow(['where' => [
            'sCardCode' => $sCardCode,
            'iStatus >' => 0
        ]]);

        $iOrderID = 0;
        if ($aCard) {
            $aOP = Model_OrderProduct::getDetail($aCard['iOPID']);
            $iOrderID = $aOP['iOrderID'];
        }

        return [$iOrderID, $aCard['iAutoID']];

    }

    /**
     * 获取所有某类型未使用的实体卡
     * （实体卡生成后状态为3，购买确定支付后状态为1）
     * @param $iPCard
     * @param $iCardType
     * @return int
     */
    public static function getNoUseRealCard($iPCard, $iCardType)
    {
        $sDate = date('Y-m-d',time());
        return self::getAll(['where' => [
            'iOrderType' => 2,
            'iStatus' => 3,
            'iCardType' => $iCardType,
            'iPCard' => $iPCard,
            'sStartDate <=' => $sDate,
            'sEndDate >' => $sDate,
        ]]);
    }

    /**
     * 生成实体卡处理
     */
    public static function initRealCard($iPCard, $iCardType,$sInitNo,$sStartDate,$sEndDate,$sRemark)
    {
        $aCardParam['iPCard'] = $iPCard;
        $aCardParam['iCardType'] = $iCardType;
        $aCardParam['sInitNo'] = $sInitNo;
        $aCardParam['sStartDate'] = $sStartDate;
        $aCardParam['sEndDate'] = $sEndDate;
        $aCardParam['sRemark'] = $sRemark;
        $aCardParam['iStatus'] = 3;
        $aCardParam['iOrderType'] = 2;
        $aCardParam['sCardCode'] = self::initCardCode($aCardParam['iOrderType'],$aCardParam['iPCard'],$aCardParam['iCardType']);
        $aCardParam['sSerialCode'] = self::initCardSerial();
        return self::addData($aCardParam);
    }

    /**
     * 生成卡处理
     * @param $iOrderType 订单类型,1=电子卡，2=实体卡,3=体检产品，4=体检计划
     * @param $iPayType 支付方式(1=个人支付，2=公司支付)
     * @param $iCreateUserID 创建人ID
     * @param $iCreateUserType 创建人类型1=个人用户，2=hr用户
     * @param $iOPID t_order_produtct的autoID
     * @param $iOrderID t_order_info的订单ID
     * @param array $aParam 非必填的参数
     * @return int
     */
    public static function initCard($iOrderType, $iPayType, $iCreateUserID, $iCreateUserType,$iOPID, $iOrderID, $aParam = array())
    {
        $aCardParam['sCardCode'] = self::initCardCode($iOrderType);
        $aCardParam['iOrderType'] = $iOrderType;
        $aCardParam['iPayType'] = $iPayType;
        $aCardParam['iCreateUserID'] = $iCreateUserID;
        $aCardParam['iCreateUserType'] = $iCreateUserType;
        $aCardParam['iOPID'] = $iOPID;
        $aCardParam['iOrderID'] = $iOrderID;

        //新加一个公司ID字段，目前只能在这里重新判断了添加，不然好多地方要改
        if ($iCreateUserType == self::PAYTYPE_COMPANY) {
            $aCardParam['iCompanyID'] = $iCreateUserID;
        } else {
            $aCustomer = Model_Customer::getDetail($iCreateUserID);
            $aCardParam['iCompanyID'] = $aCustomer['iCreateUserID'];
        }

        //以上为必填的

        if (!empty($aParam['iUserID'])) {
            $aCardParam['iUserID'] = $aParam['iUserID'];
        }
        if (!empty($aParam['iPCard'])) {
            $aCardParam['iPCard'] = $aParam['iPCard'];
        }
        if (!empty($aParam['iCardType'])) {
            $aCardParam['iCardType'] = $aParam['iCardType'];
        }
        if (!empty($aParam['iUseType'])) {
            $aCardParam['iUseType'] = $aParam['iUseType'];
        }
        if (!empty($aParam['iResoure'])) {
            $aCardParam['iResoure'] = $aParam['iResoure'];
        }
        if (!empty($aParam['iBindStatus'])) {
            $aCardParam['iBindStatus'] = $aParam['iBindStatus'];
        }
        if (!empty($aParam['iSendStatus'])) {
            $aCardParam['iSendStatus'] = $aParam['iSendStatus'];
        }
        if (!empty($aParam['iPaperReport'])) {
            $aCardParam['iPaperReport'] = $aParam['iPaperReport'];
        }
        if (!empty($aParam['iPhysicalType'])) {
            $aCardParam['iPhysicalType'] = $aParam['iPhysicalType'];
        }
        if (!empty($aParam['sStartDate'])) {
            $aCardParam['sStartDate'] = $aParam['sStartDate'];
        }
        if (!empty($aParam['sEndDate'])) {
            $aCardParam['sEndDate'] = $aParam['sEndDate'];
        }
        if (!empty($aParam['iStatus'])) {//确定支付后，该卡为可用状态
            $aCardParam['iStatus'] = $aParam['iStatus'];
        }
        if (!empty($aParam['iSex'])){
            $aCardParam['iSex'] = $aParam['iSex'];
        }
        if (!empty($aParam['sProductName'])){
            $aCardParam['sProductName'] = $aParam['sProductName'];
        }
        if (!empty($aParam['iPlanID'])){
            $aCardParam['iPlanID'] = $aParam['iPlanID'];
        }
        if (!empty($aParam['sPlanSerialNumber'])){
            $aCardParam['sPlanSerialNumber'] = $aParam['sPlanSerialNumber'];
        }
        if (!empty($aParam['iResoure'])){
            $aCardParam['iResoure'] = $aParam['iResoure'];
        }
        if (!empty($aParam['iTiJianYear'])){
            $aCardParam['iTiJianYear'] = $aParam['iTiJianYear'];
        }
        if (!empty($aParam['sThirdOrderNum'])){
            $aCardParam['sThirdOrderNum'] = $aParam['sThirdOrderNum'];
        }

        //以上为选填
        if ($iOrderType == self::ORDERTYPE_RCARD) {//实体卡
            $aCardParam['sCardCode'] = self::initCardCode($iOrderType,$aCardParam['iPCard'],$aCardParam['iCardType']);
            $aCards = self::getNoUseRealCard($aCardParam['iPCard'], $aCardParam['iCardType']);
            if (empty($aCards)) {
                return -1;//没有足够该类型的实体卡，请后台先生成实体卡
            }
            $aCardParam['iAutoID'] = $aCards[0]['iAutoID'];
            if (self::updData($aCardParam)) {
                return $aCardParam['iAutoID'];
            } else {
                return -2;//实体卡购买状态修改失败
            }
        }
        return self::addData($aCardParam);
    }

	public static function getProductByCardID ($iCardID) {
		$sqlproduct = "select cp.iAutoID as ocpID, cp.iStatus, p.sProductName from " . self::TABLE_NAME . ' AS c left join t_order_card_product cp on c.iAutoID = cp.iCardID
				left join t_product p on cp.iProductID = p.iProductID where c.iAutoID ='. $iCardID;

		$products = self::query($sqlproduct);
		return $products;
	}


    /*
     * 获取某体检计划下的卡
     * $iBookStatus 1取所有人，2取未预约的人
     */
    public static function getCardByPlan($iPlanID, $iBookStatus, $starttime = 0, $endtime = 0) {
        $sSql = "select * from ". self::TABLE_NAME. " as c where c.iOrderType = 4 and c.iPlanID = $iPlanID";

        if(!empty($iBookStatus) && 2 == $iBookStatus) { //1取所有人，2取未预约的人
            $sSql .=  " and c.iBookStatus = 0";
        }

        if(!empty($starttime) && !empty($endtime)) {
            $sSql .=  " and c.iCreateTime >= $starttime and c.iCreateTime <= $endtime";
        }

        return self::query($sSql);
    }

    /**
     * 公司体检创建卡 不创建订单
     * @param  [data]
     * @return [string]
     */
    public static function createCard ($data, $iCreateUserID)
    {
        $sID = '';
        $iCreateUserType = 2;
        $iOrderType = $data['stype'] == 2 ? 4 : 3; //4.体检计划  3.体检产品

        if ($iOrderType == 4) {        
            $aPlanProduct = Model_Physical_PlanProduct::getAll([
                'where' => [
                    'iPlanID' => $data['iPlanID'],
                    'iStatus' => Model_Physical_PlanProduct::STATUS_VALID,
                ]
            ]);
            if ($aPlanProduct) {
                foreach ($aPlanProduct as $pk => $pv) {
                    if ($pv['iProductID']) {
                        $aPlanProductID[] = $pv['iProductID'];
                    }
                }
            }
            foreach ($data['aUserID'] as $key => $value) {
                $card = Model_OrderCard::getRow(['where' => [
                    'iPlanID' => $data['iPlanID'],
                    'iUserID' => $value,
                    'iStatus IN' => ['-99', 1]
                ]]);
                if (!$card) {
                    continue;
                } 

                //已预约的体检产品和计划都不能修改(未预约 预约失败 退订的非升级产品可以修改)
                $aAMOCP = Model_OrderCardProduct::getAll([
                    'where' => [
                        'iCardID' => $card['iAutoID'],
                    ]
                ]);

                $return = 0;
                if ($aAMOCP) {
                    foreach ($aAMOCP as $sa => $sav) {
                        if ($sav['iStatus'] == 3) {
                            $return = 1;
                            break;
                        }

                        if ($sav['iBookStatus'] == 6 
                            || $sav['iBookStatus'] == 0
                            || ($sav['iBookStatus'] == 3 && $sav['iLastProductID'] == 0)
                        ) {
                            $return = 0;
                        } else {
                            $return = 1;
                            break;
                        }
                    } 

                    if ($return == 1) {
                        continue;
                    } 
                }
                
                $iSex = $data['aAttribute'][$value];
                $iPayType = $data['aPayType'][$value];
                $iStatus = ($iPayType == 1) ? 1 : 1;    //公司付款 不可用(需admin确认付款后才能用)、个人支付 有效
                $aCard = [
                    'iAutoID'       => $card['iAutoID'],
                    'iUserID'       => $value,
                    'iSex'          => $iSex,
                    'iPaperReport'  => $data['aPaperReport'][$value],
                    'iPhysicalType' => $data['aPhysicalType'][$value],
                    'sStartDate'    => $data['aStartDate'][$value],
                    'sEndDate'      => $data['aEndDate'][$value],
                    'iStatus'       => $iStatus,
                    'iUseType'      => $data['aUseType'][$value],
                    'iBindStatus'   => 1,
                    'iPayType'      => $iPayType
                ];
                
                $iProductID = $data['aProductID'][$value]; 
                if ($iProductID) {
                    $aCard['iUseAll'] = 2;
                }
                if (!$iProductID) {
                    $aCard['iUseAll'] = 1;
                }

                Model_OrderCard::updData($aCard);
                $aIDs[] = $card['iAutoID'];
                $aUser = Model_User::getDetail($iCreateUserID);
                if (!$iProductID) {
                    foreach ($aPlanProductID as $sk => $sv) {
                        $aMOCP = Model_OrderCardProduct::getRow([
                            'where' => [
                                'iProductID' => $sv,
                                'iCardID' => $card['iAutoID'],
                            ]
                        ]);
                        $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                        if ($aMOCP && $aMOCP['iStatus'] != 3) {
                            $aMOCP['iStatus'] = 1;
                            $aMOCP['sProductName'] = $aProduct['sProductName'];
                            Model_OrderCardProduct::updData($aMOCP);
                        } 
                        if (!$aMOCP) {
                            Model_OrderCardProduct::initCardProduct($card['iAutoID'], $sv, $aProduct['sProductName'], 0, 0);
                        }
                    }
                } else {
                    if ($aAMOCP) {
                        foreach ($aPlanProductID as $sk => $sv) {
                            $aCP = Model_OrderCardProduct::getRow([
                                'where' => [
                                    'iProductID' => $sv,
                                    'iCardID' => $card['iAutoID'],
                                ]
                            ]);
                            if ($aCP) {
                                if ($aCP['iStatus'] != 3) {
                                    if ($sv != $iProductID) { 
                                        if ($data['aUseType'][$value] == 1) {
                                            $aCP['iStatus'] = 0;    
                                        } else {
                                            $aCP['iStatus'] = 2;
                                        }
                                    } else {
                                        $aCP['iStatus'] = 1;
                                    }
                                    $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                                    $aCP['sProductName'] = $aProduct['sProductName'];
                                    Model_OrderCardProduct::updData($aCP);  
                                }
                            } else {
                                if ($sv != $iProductID) { 
                                    if ($data['aUseType'][$value] == 1) {
                                        $aCP['iStatus'] = 0;    
                                    } else {
                                        $aCP['iStatus'] = 2;
                                    }
                                } else {
                                    $aCP['iStatus'] = 1;
                                }
                                $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                                Model_OrderCardProduct::initCardProduct($card['iAutoID'], $sv, $aProduct['sProductName'], 0, 0, $aCP);
                            }
                        }
                    } else {
                        foreach ($aPlanProductID as $sk => $sv) {
                            $aCP = [];
                            if ($sv != $iProductID) {
                                continue;
                            }
                            $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                            Model_OrderCardProduct::initCardProduct($card['iAutoID'], $sv, $aProduct['sProductName'], 0, 0, $aCP);
                        }
                    }
                }
                
            }
        } else {
            $aUser = Model_User::getDetail($iCreateUserID);
            foreach ($data['aUserID'] as $key => $value) {
                $iProductID = $data['iHRProductID'];
                $aProduct = Model_UserProductBase::getUserProductBase($iProductID, $iCreateUserID, 1, $aUser['iChannelID']);

                $iSex = $data['aAttribute'][$value];
                $iPayType = $data['aPayType'][$value];
                $iStatus = ($iPayType == 1) ? 1 : 1;    //公司付款 不可用(需admin确认付款后才能用)、个人支付 有效
                $aCard = [
                    'iUserID'       => $value,
                    'iSex'          => $iSex,
                    'iPaperReport'  => $data['aPaperReport'][$value],
                    'iPhysicalType' => $data['aPhysicalType'][$value],
                    'sStartDate'    => $data['aStartDate'][$value],
                    'sEndDate'      => $data['aEndDate'][$value],
                    'iStatus'       => $iStatus,
                    'iBindStatus'   => 1
                ];
                $iCardID = self::initCard($iOrderType, $iPayType, $iCreateUserID, $iCreateUserType,0, 0, $aCard);
                $aIDs[] = $iCardID;

                $iOCPID = Model_OrderCardProduct::initCardProduct($iCardID, $iProductID, $aProduct['sProductName'], 0, 0);
            }
        }

        $sID = $aIDs ? implode(',', $aIDs) : '';
        return $sID;
    }

    /**
     * 体检计划添加产品后要更新card_product表
     * @param [type] $iPlanID [description]
     */
    public static function addItem ($iPlanID, $iProductID)
    {
        $aCard = Model_OrderCard::getAll(['where' => [
            'iPlanID' => $iPlanID,
            'iStatus' => 1
        ]]);

        $aPP = Model_Physical_PlanProduct::getPlanProduct($iPlanID);
        if ($iProductID && $aCard) {
            foreach ($aCard as $key => $value) {
                $param = [];
                $aCP = Model_OrderCardProduct::getAll(['where' => [
                    'iCardID' => $value['iAutoID'],
                    // 'iStatus' => 1
                ]]);
                if (!$aCP) {
                    continue;
                }
                $iCP = count($aCP);

                $row = Model_OrderCardProduct::getRow(['where' => [
                    'iProductID' => $iProductID,
                    'iCardID' => $value['iAutoID'],
                    // 'iStatus >' => 0
                ]]);
                if ($row) {
                    continue;
                }
                if ($value['iUseType'] == 1) {
                    //如果是全部选择 则插入cp表 
                    if ($value['iUseAll'] == 1) {
                        $flag = 0;
                        foreach ($aCP as $k => $v) {
                            if ($v['iUseStatus']) {
                                $flag = 1;
                            }
                        } 
                        if ($flag) {
                           $param = ['iStatus' => 2];
                        }
                    } else {
                        continue;
                    }
                }

                $aProduct = Model_UserProductBase::getUserProductBase($iProductID, $iCreateUserID, 1, $aUser['iChannelID']);
                Model_OrderCardProduct::initCardProduct($value['iAutoID'], $iProductID, $aProduct['sProductName'], 0, 0, $param);
            }
        }
    }

    /**
     * 检测产品是否有预约 有预约不可删除
     * @return 
     */
    public static function checkIsUsed ($iPlanID, $iProductID)
    {
        $aCard = Model_OrderCard::getAll(['where' => [
            'iPlanID' => $iPlanID,
            'iStatus' => 1
        ]]);

        if ($aCard) {
            foreach ($aCard as $key => $value) {
                $aCardID[] = $value['iAutoID'];
            }

            $aMOCP = Model_OrderCardProduct::getAll(['where' => [
                'iCardID IN' => $aCardID,
                'iProductID' => $iProductID,
                'iBookStatus >' => 0,
            ]]);
            if ($aMOCP) {
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * 体检计划删除产品后要更新card_product表
     * @param [type] $iPlanID [description]
     */
    public static function delItem ($iPlanID, $iProductID)
    {
        $aCard = Model_OrderCard::getAll(['where' => [
            'iPlanID' => $iPlanID,
            'iStatus' => 1
        ]]);

        if ($iProductID && $aCard) {
            foreach ($aCard as $key => $value) {
                $param = [];
                $row = Model_OrderCardProduct::getRow(['where' => [
                    'iProductID' => $iProductID,
                    'iCardID' => $value['iAutoID'],
                    'iStatus <' => 3
                ]]);
                if ($row) {
                    // $row['iStatus'] = 0;
                    Model_OrderCardProduct::realDelData($row['iAutoID']);
                }
            }
        }
    }

    //通过order_card表主键获得相关卡人人员信息
    public static function getCardinfoByIDs($iCardIDs){
        $sql = "select card.*, c.sRealName, c.iUserID, c.sMobile, c.sEmail personEmail from ". self::TABLE_NAME. " as card".
            " left join ". Model_Customer::TABLE_NAME. " as c on card.iUserID = c.iUserID".
            " where card.iAutoID in($iCardIDs)";

        return self::query($sql);
    }

    /**
     * 获取树状体检计划图 一个体检计划下多个体检产品预约
     * @param  integer $iUserID  [description]
     * @return [type]            [description]
     */
    public static function getTree ($iUserID, $iPage = 1, $aParam = [])
    {
        $aWhere = array(
            'iUserID' => $iUserID,
            'iStatus' => 1,
            'iBindStatus' => 1
        );
        $aList = self::getList($aWhere, $iPage, 'iUpdateTime DESC');
        $where = [];
        $aParam['sStartDate'] ? $where['iOrderTime >='] = strtotime($aParam['sStartDate']) : '';
        $aParam['sEndDate'] ? $where['iOrderTime <'] = strtotime($aParam['sEndDate']) : '';
        isset($aParam['iStatus']) && ($aParam['iStatus'] != -1) ? $where['iBookStatus'] = $aParam['iStatus'] : $where['iBookStatus >'] = Model_Physical_Product::STATUS_UNCONFIRM;
        // 排序及整理
        $aParent = $aParentID = [];
        if ($aList['aList']) {
            foreach ($aList['aList'] as $key => $aMenu) {
                $aParentID[] = $aMenu['iAutoID'];
                $aParent[0][$key] = $aMenu;
                $aParent[0][$key]['iMenuID'] = $aMenu['iAutoID'];      
                $aParent[0][$key]['iCardID'] = $aMenu['iAutoID'];                
                $aParent[0][$key]['sCardCode'] = $aMenu['sCardCode'];
                $aParent[0][$key]['iParentID'] = 0;
                $aParent[0][$key]['sUrl'] = '';
                $aParent[0][$key]['aDetail'] = [];
                if ($aMenu['iOrderType'] != 3) {
                    if ($aMenu['iOrderType'] == 4 && $aMenu['iPlanID'] > 0) {
                        $aPlan = Model_Physical_Plan::getDetail($aMenu['iPlanID']);
                        if ($aPlan['iStatus'] != 1) {
                            unset($aParent[0][$key]);
                            continue;
                        }    
                        $aParent[0][$key]['sMenuName'] = '员工体检计划';
                    }
                    if ($aMenu['iOrderType'] == 1 || $aMenu['iOrderType'] == 2) {
                        $aParent[0][$key]['sMenuName'] = '产品体检卡';
                    }        
                    
                    $where['iCardID'] = $aMenu['iAutoID'];
                    $where['iStatus >'] = 0;
                    if (!$where['iBookStatus']) {
                        $where['iBookStatus !='] = 4;
                    }
                    $aCP = Model_OrderCardProduct::getAll(['where' => $where]);
                    if ($aCP) {
                        foreach ($aCP as $k => &$v) {
                            $desc = '';
                            
                            $aParent[$aMenu['iAutoID']][$k] = $v;
                            $aParent[$aMenu['iAutoID']][$k]['iMenuID'] = $v['iAutoID'];
                            $aParent[$aMenu['iAutoID']][$k]['sMenuName'] = $v['sProductName'].$desc;
                            $aParent[$aMenu['iAutoID']][$k]['iParentID'] = $v['iCardID'];
                            $aParent[$aMenu['iAutoID']][$k]['sCardCode'] = $aMenu['sCardCode'];
                            $aParent[$aMenu['iAutoID']][$k]['sUrl'] = '';

                            $iChannelType = ($aMenu['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT || $aMenu['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT_PLAN) ? 1 : 2;
                            $aCreateUser = Model_User::getDetail($aMenu['iCreateUserID']);
                            $aProduct = Model_UserProductBase::getUserProductBase($v['iProductID'], $aMenu['iCreateUserID'], $iChannelType, $aCreateUser['iChannel']);
                            $aParent[$aMenu['iAutoID']][$k]['sProductCode'] = !empty($aProduct['sProductCode']) ? $aProduct['sProductCode'] : '';
                            $aParent[$aMenu['iAutoID']][$k]['sBookStatus'] = !empty(self::$aStatus[$v['iBookStatus']]) ? self::$aStatus[$v['iBookStatus']] : '';
                            $aParent[$aMenu['iAutoID']][$k]['sUseStatus'] = 1 == $v['iUseStatus'] ? '已使用' : '未使用';
                            $aParent[$aMenu['iAutoID']][$k]['sEndDate'] = $aMenu['sEndDate'];  
                            $aParent[$aMenu['iAutoID']][$k]['sUseable'] =  1 == $v['iStatus'] ? '可用' : '不可用';   
                            $aParent[$aMenu['iAutoID']][$k]['iUseable'] = $v['iStatus'];
                            $aParent[$aMenu['iAutoID']][$k]['iCheckRefund'] = $v['iCheckRefund'];
                            $aParent[$aMenu['iAutoID']][$k]['iLastProductID'] = $v['iLastProductID'];
                            if ($v['iLastProductID'] > 0) {
                                $desc = '(已升级)';
                            }
                            Model_Refund::ifCanRefund($aMenu,$v);
                            $aParent[$aMenu['iAutoID']][$k]['iRefunding'] = $v['iRefunding'];
                            $aParent[$aMenu['iAutoID']][$k]['iRefundID'] = $v['iRefundID'];
                            $aParent[$aMenu['iAutoID']][$k]['iCanRefund'] = $v['iCanRefund'];
                            $aParent[$aMenu['iAutoID']][$k]['iRefundMoney'] = $v['iRefundMoney'];
                            $aParent[$aMenu['iAutoID']][$k]['sUpgradeOrderCode'] = $v['sUpgradeOrderCode'];
                            $aParent[$aMenu['iAutoID']][$k]['sUpgradePayOrderID'] = $v['sUpgradePayOrderID'];
                            $aParent[$aMenu['iAutoID']][$k]['sOrderCode'] = $v['sOrderCode'];
                            $aParent[$aMenu['iAutoID']][$k]['sPayOrderID'] = $v['sPayOrderID'];
                            $aParent[$aMenu['iAutoID']][$k]['iRefundMoney1'] = $v['iRefundMoney1'];
                            $aParent[$aMenu['iAutoID']][$k]['iRefundMoney2'] = $v['iRefundMoney2'];
                            $aParent[$aMenu['iAutoID']][$k]['aDetail'][] = array_merge($aProduct, $v);
                        }
                    } else {
                        unset($aParent[0][$key]);
                    }
                } else {
                    $aParent[0][$key]['sMenuName'] = '体检产品';
                    $where['iCardID'] = $aMenu['iAutoID'];
                    $where['iStatus >'] = 0;
                    if (!$where['iBookStatus']) {
                        $where['iBookStatus !='] = 4;
                    }                    
                    $desc = '';
                    $aCP = Model_OrderCardProduct::getRow(['where' => $where]);
                    if ($aCP['iLastProductID'] > 0) {
                        // $desc = '(已升级)';
                    }
                    if ($aCP) {
                        $aParent[0][$key]['iMenuID'] = $aMenu['iAutoID'];               
                        $aParent[0][$key]['sProductName'] = $aCP['sProductName'].$desc;
                        $aParent[0][$key]['iProductID'] = $aCP['iProductID'];
                        $aParent[0][$key]['iOrderTime'] = $aCP['iOrderTime'];
                        $aParent[0][$key]['sBookStatus'] = !empty(self::$aStatus[$aCP['iBookStatus']]) ? self::$aStatus[$aCP['iBookStatus']] : '';
                        $aParent[0][$key]['sUseStatus'] =  1 == $aCP['iUseStatus'] ? '已使用' : '未使用';
                        $aParent[0][$key]['iBookStatus'] = $aCP['iBookStatus'];
                        $aParent[0][$key]['iUseStatus'] = $aCP['iUseStatus'];
                        $aParent[0][$key]['sEndDate'] = $aMenu['sEndDate'] && $aMenu['sEndDate'] != '0000-00-00' ? $aMenu['sEndDate'] : '0000-00-00';
                        $aParent[0][$key]['sUseable'] =  1 == $aCP['iStatus'] ? '可用' : '不可用';
                        $aParent[0][$key]['iUseable'] = $aCP['iStatus'];
                        $aParent[0][$key]['iCardID'] = $aMenu['iAutoID']; 
                        $aParent[0][$key]['iLastProductID'] = $aCP['iLastProductID'];

                        Model_Refund::ifCanRefund($aMenu,$aCP);
                        $aParent[0][$key]['iRefunding'] = $aCP['iRefunding'];
                        $aParent[0][$key]['iRefundID'] = $aCP['iRefundID'];
                        $aParent[0][$key]['iCanRefund'] = $aCP['iCanRefund'];
                        $aParent[0][$key]['iRefundMoney'] = $aCP['iRefundMoney'];
                        $aParent[0][$key]['sUpgradeOrderCode'] = $aCP['sUpgradeOrderCode'];
                        $aParent[0][$key]['sUpgradePayOrderID'] = $aCP['sUpgradePayOrderID'];
                        $aParent[0][$key]['sOrderCode'] = $aCP['sOrderCode'];
                        $aParent[0][$key]['sPayOrderID'] = $aCP['sPayOrderID'];
                        $aParent[0][$key]['iRefundMoney1'] = $aCP['iRefundMoney1'];
                        $aParent[0][$key]['iRefundMoney2'] = $aCP['iRefundMoney2'];
                    } else {
                        unset($aParent[0][$key]);
                    }
                }
            }
        }
        return [self::_buildTree($aParent, 0, 0, ''), $aList['iTotal'], $aList['aPager']];
    }

    private static function _buildTree ($aParent, $iParentID, $iLevel, $sPath)
    {
        $aTree = array();
        if (isset($aParent[$iParentID])) {
            foreach ($aParent[$iParentID] as $aMenu) {
                $aMenu['iLevel'] = $iLevel;
                $aMenu['sPath'] = $sPath;
                $aMenu['aChild'] = self::_buildTree($aParent, $aMenu['iMenuID'], $iLevel + 1, $sPath . ' menup' . $aMenu['iMenuID']);
                $aTree[] = $aMenu;
            }
        }

        return $aTree;
    }

    /**
     * 检查产品是否已经预约或者已付款 如果是 则不能更改产品信息
     * 已预约 不能改
     * 个人已付款 不能改
     * @return [type] [description]
     */
    public static function checkIsPayOrAppoinment ($aCard)
    {
        if ($aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT 
        || $aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT_PLAN) {
            $aCP = Model_OrderCardProduct::getAllCardProduct($aCard['iAutoID']);
            if ($aCP) {
                foreach ($aCP as $key => $value) {
                    if ($value['iPayStatus'] == 1 || in_array($value['iBookStatus'], [1, 2, 5])) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * 判断用户与绑定的卡性别是否一致
     * @return [type] [description]
     */
    public static function checkSex ($iCardID, $iSex, $iChannelID)
    {
        $aSex = Yaf_G::getConf('aSex');
        $aCard = self::getDetail($iCardID);
        if ($aCard['iSex'] != $iSex) {
            //性别不符
            $aCardProductID = Model_OrderCardProduct::getProductByCardIDs($aCard['iAutoID']);
            if (!empty($aCardProductID)) {
                $iChannelType = ($aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT 
                    || $aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT_PLAN)
                    ? 1 : 2;
                foreach ($aCardProductID as $key => $value) {
                    $aProduct = Model_UserProductBase::getUserProductBase($value, $aCard['iCompanyID'], $iChannelType, $iChannelID);
                    if (!empty($aProduct)) {
                        if (!empty($aProduct['iNeedSex'])) {
                            return '该卡含有仅支持'.$aSex[$aCard['iSex']].'的产品套餐和您的性别不符！';
                        }
                    } else {
                        return '该卡含有不符合你渠道的产品，请联系客服！';
                    }
                }
            } else {
                return '该卡不含有效产品套餐，请联系客服！';
            }
        }

        return 0;
    }

    /**
     * AF创建体检计划 不创建订单
     * @param  [data]
     * @return [string]
     */
    public static function createCard2 ($data, $iCreateUserID)
    {
        $sID = '';
        $aIDs = [];
        $iCreateUserType = 2;
        $iOrderType = $data['stype'] == 2 ? 4 : 3; //4.体检计划  3.体检产品

        if ($iOrderType == 4) { 
            $aPlanProduct = Model_Physical_PlanProduct::getAll([
                'where' => [
                    'iPlanID' => $data['iPlanID'],
                    'iStatus' => Model_Physical_PlanProduct::STATUS_VALID,
                ]
            ]);
            if ($aPlanProduct) {
                foreach ($aPlanProduct as $pk => $pv) {
                    if ($pv['iProductID']) {
                        $aPlanProductID[] = $pv['iProductID'];
                    }
                }
            }

            foreach ($data['aUserID'] as $key => $value) {
                //入职体检可以多次 年度体检只能一次
                if ($data['aPhysicalType'][$value] == 1) {
                    $card = Model_OrderCard::getRow(['where' => [
                        'iPlanID' => $data['iPlanID'],
                        'iPhysicalType' => 1,
                        'iUserID' => $value,
                        'iStatus IN' => [1, '-99']
                    ]]);
                    if ($card) {
                        continue;
                    }
                }

                $aCard = [
                    'iUserID'       => $value,
                    'iPlanID'       => $data['iPlanID'],
                    'sCardCode'     => Model_OrderCard::initCardCode(),
                    'iOrderType'    => 4,
                    'iSex'          => $data['aAttribute'][$value],
                    'iPaperReport'  => $data['aPaperReport'][$value],
                    'iPhysicalType' => $data['aPhysicalType'][$value],
                    'sStartDate'    => $data['aStartDate'][$value],
                    'sEndDate'      => $data['aEndDate'][$value],
                    'iStatus'       => 1,
                    'iUseType'      => $data['aUseType'][$value],
                    'iBindStatus'   => 1,
                    'iPayType'      => $data['aPayType'][$value],
                    'iUseAll'       => 2,
                    'iResoure'      => 1,
                    // AF推送自带
                     'sThirdOrderNum' => $data['sThirdOrderNum'],
                     'sPlanSerialNumber' => $data['sPlanSerialNumber'],
                     'iTiJianYear' => $data['iTiJianYear'],
                     'iCompanyID' => $data['iCompanyID'],
                    'iCreateUserID' => $data['iCreateUserID'],
                    'iCreateUserType' => $data['iCreateUserType']
                ];

                $iCardID = Model_OrderCard::addData($aCard);
                $aIDs[] = $iCardID;
                
                //已预约的体检产品和计划都不能修改(未预约 预约失败 退订的非升级产品可以修改)
                $aAMOCP = Model_OrderCardProduct::getAll([
                    'where' => [
                        'iCardID' => $iCardID,
                    ]
                ]);
                if ($aAMOCP) {
                    foreach ($aAMOCP as $sa => $sav) {
                        if ($sav['iStatus'] == 3) {
                            $return = 1;
                            break;
                        }

                        if ($sav['iBookStatus'] == 6 
                            || $sav['iBookStatus'] == 0
                            || ($sav['iBookStatus'] == 3 && $sav['iLastProductID'] == 0)
                        ) {
                            $return = 0;
                        } else {
                            $return = 1;
                            break;
                        }
                    } 

                    if ($return == 1) {
                        continue;
                    } 
                }

                $iProductID = $data['aProductID'][$value]; 
                $aUser = Model_User::getDetail($iCreateUserID);
                if ($aAMOCP) {
                    foreach ($aPlanProductID as $sk => $sv) {
                        $aCP = Model_OrderCardProduct::getRow([
                            'where' => [
                                'iProductID' => $sv,
                                'iCardID' => $iCardID,
                            ]
                        ]);
                        if ($sv != $iProductID) {
                            continue;
                        }
                        if ($aCP) {
                            if ($aCP['iStatus'] != 3) {
                                $aCP['iStatus'] = 1;
                                $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                                $aCP['sProductName'] = $aProduct['sProductName'];
                                Model_OrderCardProduct::updData($aCP);  
                            }
                        } else {
                            $aCP['iStatus'] = 1;
                            $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                            Model_OrderCardProduct::initCardProduct($iCardID, $sv, $aProduct['sProductName'], 0, 0, $aCP);
                        }
                    }
                } else {
                    foreach ($aPlanProductID as $sk => $sv) {
                        $aCP = [];
                        if ($sv != $iProductID) {
                            continue;
                        }
                        $aProduct = Model_UserProductBase::getUserProductBase($sv, $iCreateUserID, 1, $aUser['iChannelID']);
                        Model_OrderCardProduct::initCardProduct($iCardID, $sv, $aProduct['sProductName'], 0, 0, $aCP);
                    }
                }
            }
        }

        $sID = !empty($aIDs) ? implode(',', $aIDs) : '';
        return $sID;
    }
}