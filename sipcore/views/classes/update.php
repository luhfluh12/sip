<?php
$this->breadcrumbs=array(
	'scoala'=>array('school/view','id'=>$class->school),
	$class->grade.' '.$class->name=>array('view','id'=>$class->id),
	'',
);

$this->pageTitle='Actualizare clasă';

$this->menu=array(
	array('label'=>'List Classes', 'url'=>array('index')),
	array('label'=>'Create Classes', 'url'=>array('create')),
	array('label'=>'View Classes', 'url'=>array('view', 'id'=>$class->id)),
	array('label'=>'Manage Classes', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('class'=>$class,'teacher'=>$teacher,'account'=>$account)); ?>