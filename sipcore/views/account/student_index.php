<?php
$this->pageTitle = $account->rStudent->name;
$this->menu=array(
	array('label'=>'Schimbă mail sau parolă', 'url'=>array('update','id'=>Yii::app()->user->id)),
);

echo CHtml::link('See my stats',array('student/view','id'=>$account->info));
?>
