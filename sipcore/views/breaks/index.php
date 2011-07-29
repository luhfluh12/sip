<?php
$this->breadcrumbs=array(
	'Vacanțe',
);

$this->menu=array(
	array('label'=>'Adaugă vacanță', 'url'=>array('create')),
	array('label'=>'Administrează', 'url'=>array('admin')),
);

$this->sip_title = "Vacanțe";

?>

<h1>Lista vacanțelor școlare</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
