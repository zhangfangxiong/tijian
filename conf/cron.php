<?php
//后台进程配置 i:H:d:m:w
$config = array(
    array('path' => '/cmd/checkstatus/index', 'cron' => '30 22 * * *', 'num' => 1),//预约状态脚本
    array('path' => '/cmd/preday/index', 'cron' => '00 09 * * *', 'num' => 1),//提前一天提醒
);

return $config;
