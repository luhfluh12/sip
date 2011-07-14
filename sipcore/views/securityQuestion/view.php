<?php
$this->breadcrumbs=array(
	'Security Questions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SecurityQuestion', 'url'=>array('index')),
	array('label'=>'Create SecurityQuestion', 'url'=>array('create')),
	array('label'=>'Update SecurityQuestion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SecurityQuestion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SecurityQuestion', 'url'=>array('admin')),
);
?>

<h1>View SecurityQuestion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'question',
	),
)); ?>
