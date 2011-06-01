<?php
$this->breadcrumbs=array(
	'Breaks'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Breaks', 'url'=>array('index')),
	array('label'=>'Create Breaks', 'url'=>array('create')),
	array('label'=>'Update Breaks', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Breaks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Breaks', 'url'=>array('admin')),
);
?>

<h1>View Breaks #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'schoolyear',
		'start',
		'end',
		'name',
	),
)); ?>
