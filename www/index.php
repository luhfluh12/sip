<?php
// change the following paths if necessary
$yii='/var/yii/framework/yii.php';
$config=dirname(__FILE__).'/../sipcore/config/main.php';

require_once($yii);
Yii::createWebApplication($config)->run();
