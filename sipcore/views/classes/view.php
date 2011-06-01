<?php
$this->breadcrumbs=array(
        'Școli'=>array('school/index'),
	$school->name=>array('school/view','id'=>$school->id),
	'',
);

$this->sip_title = $class->grade.' '.$class->name.' ('.$class->profile.')';
$this->pageTitle .= ' - '.$this->sip_title;

$this->sip_tabs = array(
    'Elevi' => $this->renderPartial('tab_students',array('students'=>$class->rStudent, 'count'=>$class->rStudentCount,'class'=>$class),true),
    'Orar' => array('ajax'=>$this->createUrl('classes/schedule',array('id'=>$class->id))),
    'Info' => $this->renderPartial('tab_info',array('class'=>$class,'school'=>$school),true),
    'Rapoarte' => array('ajax'=>$this->createUrl('classes/statistics',array('id'=>$class->id))),
    'Sms-uri' => array('ajax'=>$this->createUrl('classes/sms',array('id'=>$class->id))),
);

/*<h1>Elevii clasei</h1>


?>*/