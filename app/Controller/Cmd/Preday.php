<?php
/**
 * 定时任务 到检前短信通知 每天早上9点发送
 */

class Controller_Cmd_Preday extends Controller_Cmd_Base
{

	public function indexAction ()
    {
    	$where = [
    		'iBookStatus' => 1,
            'iOrderTime >' => time(),
    		'iOrderTime <' => time() + 16*60*60 //提前16h发送
    	];

        $aCP = Model_OrderCardProduct::getAll(['where' => $where]);
        $aCardID = [];
        $aUserID = [];
        $sCardIDs = '';
        $sUserIDs = '';
        $aUsers = [];

        if ($aCP) {             
            foreach ($aCP as $key => $value) {
                if ($value['iCardID']) {
                    $aCardID[] = $value['iCardID'];    
                }                
            }

            if ($aCardID) {
                $sCardIDs = implode(',', $aCardID);
            }

            $aCard =  Model_OrderCard::getListByPKIDs($sCardIDs);
            if ($aCard) {
                foreach ($aCard as $key => $value) {
                    if ($value['iUserID']) {
                        $aUserID[] = $value['iUserID'];
                    }
                }

                if ($aUserID) {
                    $sUserIDs = implode(',', $aUserID);
                }
            }

            if ($sUserIDs) {
                $aEmpolyee = Model_CustomerNew::getListByPKIDs($sUserIDs);
                if ($aEmpolyee) {
                    foreach ($aEmpolyee as $key => $value) {
                        $aUsers[$value['iUserID']]['sRealName'] = $value['sRealName'];
                        $aUsers[$value['iUserID']]['sMobile'] = $value['sMobile'];
                    }
                }

                $tmp = [];
                foreach ($aCP as $key => $value) {
                    $card = Model_OrderCard::getDetail($value['iCardID']);
                    $tmp['sRealName'] = $aUsers[$card['iUserID']]['sRealName'];                    
                    $tmp['sMobile'] = $aUsers[$card['iUserID']]['sMobile'];
                    $tmp['sPhysicalDate'] = date('Y-m-d', $value['iOrderTime']);
                    $tmp['sProductName'] = $value['sProductName'];

                    $aStore = Model_Store::getDetail($value['iStoreID']);
                    $tmp['sStoreName'] = $aStore['sName'];
                    $tmp['sAddress'] = $aStore['sAddress'];
                    
                    $res = $this->sendMail($tmp);                    
                }
            }
        }
    	
        echo "发送完成";
    }


    /**
     * 短信模板
     * @param  [type] $tmp [description]
     * @return [type]      [description]
     */
    public function sendMail ($tmp)
    {
        $msg = Yaf_G::getConf('predaymsg', 'physical');
        $msg = preg_replace('/\【员工姓名\】/', $tmp['sRealName'], $msg);
        $msg = preg_replace('/\【体检日期\】/', $tmp['sPhysicalDate'], $msg);
        $msg = preg_replace('/\【体检套餐\】/', $tmp['sProductName'], $msg);
        $msg = preg_replace('/\【体检门店\】/', $aStore['sStoreName'], $msg);
        $msg = preg_replace('/\【体检地点\】/', $aStore['sAddress'], $msg);

        $smsRes = Sms_Joying::sendBatch($tmp['sMobile'], $msg);
        if ($smsRes) {
            return ture;   
        }

        return false;
    }
}