<?php
$this->breadcrumbs=array(
	'Security Questions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SecurityQuestion', 'url'=>array('index')),
	array('label'=>'Create SecurityQuestion', 'url'=>array('create')),
	array('label'=>'View SecurityQuestion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SecurityQuestion', 'url'=>array('admin')),
);
?>

<h1>Update SecurityQuestion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>