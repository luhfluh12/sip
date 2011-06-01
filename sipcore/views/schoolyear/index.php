<?php
$this->breadcrumbs=array(
	'Schoolyears',
);

$this->menu=array(
	array('label'=>'Create Schoolyear', 'url'=>array('create')),
	array('label'=>'Manage Schoolyear', 'url'=>array('admin')),
);
?>

<h1>Schoolyears</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
