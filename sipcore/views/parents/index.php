<?php
$this->breadcrumbs=array(
	'Parents',
);

$this->menu=array(
	array('label'=>'Create Parents', 'url'=>array('create')),
	array('label'=>'Manage Parents', 'url'=>array('admin')),
);
?>

<h1>Parents</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
