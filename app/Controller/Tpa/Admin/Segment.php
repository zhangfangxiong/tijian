<?php

/**
 * 数据管理module
 */

class Controller_Tpa_Admin_Segment extends Controller_Tpa_Admin_Base
{

	public $aStaff = null;

	public $aPrintStatus = null;

	public $aSegmentNormal = null;

	public function actionBefore ()
	{
		parent::actionBefore();

		$this->aPrintStatus = Yaf_G::getConf('aPrintStatus', null, 'tpa');
		
		$this->aStaff = Model_Tpa_User::getPair();

		$this->aSegmentNormal = Yaf_G::getConf('aSegmentNormal', null, 'tpa');
	}
	
	/**
	 * 检测列表参数
	 * @return [array]
	 */
	protected function checkListParam ()
	{
		$aParams = $this->getParams();
		
		$where = [];
		if (!empty($aParams['sStartDate'])) {
			$where['sDealDate >='] = $aParams['sStartDate'];
		}

		if (!empty($aParams['sEndDate'])) {
			$where['sDealDate <='] = $aParams['sEndDate'];	
		}

		switch ($aParams['iPrintStatus']) {
			case '0':
				$where['iPrintNumber'] = 0;
				break;
			case '1':
				$where['iPrintNumber >'] = 0;
				break;
			case '2':
				$where['iPrintNumber >'] = 1;
				break;				
			default:					
				break;
		}

		$this->assign('aParams', $aParams);
		return $where;
	}

	/**
	 * 获取打印状态
	 * @param  [int] $iPrintNumber
	 * @return [string] $sPrintStatus
	 */
	public function getPrintStatus ($iPrintNumber)
	{
		$state = 0;
		if ($iPrintNumber > 0) {
			$state = 1;
		}

		return $this->aPrintStatus[$state];
	}

	/**
	 * 受理编号查询列表
	 * @return [array List]
	 */
	public function listAction ()
	{
		$where = $this->checkListParam();
		$page = max($this->getParam('page'), 1);
		$aList = Model_Tpa_Segment::getList($where, $page);

		if ($aList['iTotal'] > 0) {
			foreach ($aList['aList'] as $key => &$value) {
				$value['sAssignFrom'] = $this->aStaff[$value['iCreateUserID']];
				$value['sAssignTo'] = $this->aStaff[$value['iAssignedUserID']];
				$value['sPrintStatus'] = $this->getPrintStatus($value['iPrintNumber']);
				$value['sAssignTime'] = date('Y-m-d H:i:s', $value['iCreateTime']);
			}
		}
		
		$this->assign('aList', $aList);
	}

	/**
	 * 生成号段参数
	 * @return [array]
	 */
	public function checkAddParam ()
	{
		$data = [];
		$data['sDealDate'] = date('Y-m-d', time());
		$data['iNormal'] = $this->getParam('iNormal');

		list($data['iStart'], $data['iEnd']) = Model_Tpa_Segment::getSegmentRange($data['sDealDate']);
		$data['sSegmentFrom'] = $data['sDealDate'] . $this->aSegmentNormal[$data['iNormal']] . $data['iStart'];
		$data['sSegmentTo'] = $data['sDealDate'] . $this->aSegmentNormal[$data['iNormal']] . $data['iEnd'];

		return $data;
	}

	/**
	 * 生成指派号码段
	 */
	public function addSegmentAction ()
	{
		$data = $this->checkAddParam();
		if ($this->isPost()) {
			$data['iAssignedUserID'] = $this->getParam('iAssignedUserID');
			$data['iCreateUserID'] = $this->iCreateUserID;
			$data['iStatus'] = Model_Tpa_Segment::STATUS_ASSIGNED;
			Model_Tpa_Segment::addData($where);

			return $this->showMsg('成功指派', true);
		}

		$this->assign('aData', $data);
	}
}