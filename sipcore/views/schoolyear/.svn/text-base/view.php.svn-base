<?php
$this->breadcrumbs=array(
	'Schoolyears'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Schoolyear', 'url'=>array('index')),
	array('label'=>'Create Schoolyear', 'url'=>array('create')),
	array('label'=>'Update Schoolyear', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Schoolyear', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Schoolyear', 'url'=>array('admin')),
);
?>

<h1>View Schoolyear #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'start:date:Inceput an scolar',
		'change:date:Inceput semestrul al doilea',
		'end:date:Sfarsit an scolar',
	),
)); ?>
