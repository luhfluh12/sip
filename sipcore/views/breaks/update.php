<?php
$this->breadcrumbs=array(
	'Breaks'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Lista de vacanțe', 'url'=>array('index')),
	array('label'=>'Adaugă vacanță', 'url'=>array('create')),
	array('label'=>'Administrează', 'url'=>array('admin')),
);

$this->sip_title = 'Editează vacanța';
?>


<h1> <?php echo CHtml::encode($model->name); ?> (<?php echo date('j M Y',$model->start); ?> - <?php echo date('j M Y',$model->end); ?>)</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>