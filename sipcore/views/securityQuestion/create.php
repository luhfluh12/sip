<?php
$this->breadcrumbs=array(
	'Security Questions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SecurityQuestion', 'url'=>array('index')),
	array('label'=>'Manage SecurityQuestion', 'url'=>array('admin')),
);
?>

<h1>Create SecurityQuestion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>