<?php

class Model_Tpa_Region extends Model_Tpa_Base
{

    const TABLE_NAME = 't_region';

    public static function getCityByProvince($ProvinceID)
    {
        return self::getAll(array(
            'where' => array(
                'parent_id' => $ProvinceID,
            )
        ));
    }

    public static function getProvince()
    {
        return self::getAll(array(
            'where' => array(
                'region_type' => 1,
            )
        ));
    }
}