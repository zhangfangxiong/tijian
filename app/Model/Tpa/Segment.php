<?php

class Model_Tpa_Segment extends Model_Tpa_Base
{

	const TABLE_NAME = 't_case_number';

	const STATUS_UNASSIGN = 1;

	const STATUS_ASSIGNED = 2;

	/**
	 * 获取区间范围
	 * @param  [date] $sDate
	 * @return [array] [$start, $end]
	 */
	public static function getSegmentRange ($sDate)
	{
		$start = 1;
		$end = 200;
		$row = self::getRow([
			'where' => [
				'sDealDate' => $sDate,
			],
			'order' => 'iEnd Desc'
		]);

		if ($row) {
			$start += $row['iEnd'];
			$end += $row['iEnd'];
		}

		return [$start, $end];
	}

}