<?php
$this->breadcrumbs=array(
	'Parents'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Parents', 'url'=>array('index')),
	array('label'=>'Create Parents', 'url'=>array('create')),
	array('label'=>'View Parents', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Parents', 'url'=>array('admin')),
);
?>

<h1>Update Parents <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>