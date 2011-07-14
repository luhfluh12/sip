<?php
$this->breadcrumbs=array(
	'Security Questions',
);

$this->menu=array(
	array('label'=>'Create SecurityQuestion', 'url'=>array('create')),
	array('label'=>'Manage SecurityQuestion', 'url'=>array('admin')),
);
?>

<h1>Security Questions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
