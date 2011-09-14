<?php

// change the following paths if necessary
$yii='/var/yii/framework/yii.php';
$config=dirname(__FILE__).'/../sipcore/config/main.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();
