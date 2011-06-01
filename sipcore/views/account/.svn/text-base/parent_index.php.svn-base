<?php
$this->pageTitle = $parent->name;
$this->menu=array(
	array('label'=>'Schimbă mail sau parolă', 'url'=>array('update','id'=>Yii::app()->user->id)),
);

foreach ($students as $student) {
    echo CHtml::link($student->name, array('student/view','id'=>$student->id)).'<br />';
}

?>
