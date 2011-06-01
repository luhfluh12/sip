<?php
$this->breadcrumbs=array(
    'Școli'=>array('school/index'),
    $student->rSchool->name=>array('school/view','id'=>$student->rSchool->id),
    $student->rClass->grade.' '.$student->rClass->name=>array('classes/view','id'=>$student->rClass->id),
    '',
);

$this->sip_title=$student->name;
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/css/jsflot/jquery.flot.min.js');

$this->widget('system.web.widgets.CTabView',array(
    'tabs'=>array(
        'tab1'=>array(
            'title'=>'Note și absențe',
            'url'=>$this->createUrl('student/view',array('id'=>$student->id)).'#tab1',
        ),
        'tab2'=>array(
            'title'=>'Statistici',
            'view'=>'tab_stats',
            'data'=>array('averages'=>$student->rAverages),
        ),
        'tab3'=>array(
            'title'=>'SMS-uri',
            'url'=>$this->createUrl('student/sms',array('id'=>$student->id)),
        ),
        'tab4'=>array(
            'title'=>'Informații',
            'url'=>$this->createUrl('student/view',array('id'=>$student->id)).'#tab4',
        ),
    ),
    'activeTab'=>'tab2',
));