<?php

$auth = Yii::app()->authManager;

$biz = 'return Account::model()->findByPk(Yii::app()->user->id)->haveAccess($param["info"],';

$parent = $auth -> createRole('parent','parinte',$biz.Account::TYPE_PARENT.');');
$teacher = $auth -> createRole('teacher','profesor sau diriginte',$biz.Account::TYPE_TEACHER.');');
$school = $auth -> createRole('school','scoala',$biz.Account::TYPE_SCHOOL.');');
$student = $auth -> createRole('student','elev intr-o scoala',$biz.Account::TYPE_STUDENT.');');

$admin = $auth -> createRole('admin','administrator al acestui sistem');

$auth->assign('admin','vlad.velici@gmail.com');
