<?php

class Model_Refund extends Model_Base
{
    const TABLE_NAME = 't_refund';

    const REFUND_REFUNDING = 1;//退款中
    const REFUND_HASREFUND = 2;//已退款
    const REFUND_CANCEL = 3;//用户取消
    const REFUND_REFUSE = 4;//管理员拒绝
    const REFUND_TYPE1 = 1;//支付退款
    const REFUND_TYPE2 = 2;//升级退款

    //判断是否能退款
    public static function ifCanRefund($aCard, &$aCardProduct)
    {
        //判断是否满足退款条件（个人支付过的且不是已预约以后的状态都可退款）
        if (in_array($aCardProduct['iBookStatus'], [0, 3, 6])) {//未预约或已退订或预约失败状态可退款
            if (($aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT || $aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT_PLAN) && $aCardProduct['iPayStatus'] == Model_OrderCard::PAYSTATUS_1 && $aCard['iPayType'] == Model_OrderCard::PAYTYPE_PERSON) {
                //1，体检产品或体检计划的个人支付，且已支付过
                $aCardProduct['iCanRefund'] = 1;
            } elseif (!empty($aCardProduct['iLastProductID'])) {//2，已升级过
                $aCardProduct['iCanRefund'] = 1;
            }
            //判断是否退款中
            if ($aCardProduct['iCanRefund'] && $aCardProduct['iRefundID']) {
                $aCardProduct['iRefunding'] = 1;
            }
        }
        //如果退款中，显示应该退款金额
        $aCardProduct['iRefundMoney'] = 0;
        $aCardProduct['sUpgradeOrderCode'] = '';
        $aCardProduct['sUpgradePayOrderID'] = '';
        $aCardProduct['sOrderCode'] = '';
        $aCardProduct['sPayOrderID'] = '';
        if (!empty($aCardProduct['iLastProductID'])) {//升级过的
            if (!empty($aCardProduct['iLastOPID'])) {
                if (($aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT || $aCard['iOrderType'] == Model_OrderCard::ORDERTYPE_PRODUCT_PLAN) && $aCardProduct['iPayStatus'] == Model_OrderCard::PAYSTATUS_1 && $aCard['iPayType'] == Model_OrderCard::PAYTYPE_PERSON) {
                    //1，体检产品或体检计划的个人支付，且已支付过
                    $aOrderProduct = Model_OrderProduct::getDetail($aCardProduct['iLastOPID']);
                    $aCardProduct['iRefundMoney1'] = !empty($aOrderProduct['sProductPrice']) ? $aOrderProduct['sProductPrice'] : 0;
                    $aCardProduct['iRefundMoney'] += $aCardProduct['iRefundMoney1'];
                    $aOrderInfo = Model_OrderInfo::getDetail($aCardProduct['iLastOrderID']);
                    $aCardProduct['sOrderCode'] = $aOrderInfo['sOrderCode'];
                    $aCardProduct['sPayOrderID'] = $aOrderInfo['sPayOrderID'];
                }
            }
            $aOrderUpProduct = Model_OrderProduct::getDetail($aCardProduct['iOPID']);
            $aCardProduct['iRefundMoney2'] = !empty($aOrderUpProduct['sProductPrice']) ? $aOrderUpProduct['sProductPrice'] : 0;
            $aCardProduct['iRefundMoney'] += $aCardProduct['iRefundMoney2'];
            $aOrderInfo = Model_OrderInfo::getDetail($aCardProduct['iOrderID']);
            $aCardProduct['sUpgradeOrderCode'] = $aOrderInfo['sOrderCode'];
            $aCardProduct['sUpgradePayOrderID'] = $aOrderInfo['sPayOrderID'];
        } else {
            $aOrderUpProduct = Model_OrderProduct::getDetail($aCardProduct['iOPID']);
            $aCardProduct['iRefundMoney1'] = !empty($aOrderUpProduct['sProductPrice']) ? $aOrderUpProduct['sProductPrice'] : 0;
            $aCardProduct['iRefundMoney'] += $aCardProduct['iRefundMoney1'];
            $aOrderInfo = Model_OrderInfo::getDetail($aCardProduct['iOrderID']);
            $aCardProduct['sOrderCode'] = $aOrderInfo['sOrderCode'];
            $aCardProduct['sPayOrderID'] = $aOrderInfo['sPayOrderID'];
        }
    }
}