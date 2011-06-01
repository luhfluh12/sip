<?php
$this->breadcrumbs=array(
	'Schoolyears'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Schoolyear', 'url'=>array('index')),
	array('label'=>'Create Schoolyear', 'url'=>array('create')),
	array('label'=>'View Schoolyear', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Schoolyear', 'url'=>array('admin')),
);
?>

<h1>Update Schoolyear <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>