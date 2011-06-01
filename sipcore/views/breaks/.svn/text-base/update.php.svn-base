<?php
$this->breadcrumbs=array(
	'Breaks'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Breaks', 'url'=>array('index')),
	array('label'=>'Create Breaks', 'url'=>array('create')),
	array('label'=>'View Breaks', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Breaks', 'url'=>array('admin')),
);
?>

<h1>Update Breaks <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>