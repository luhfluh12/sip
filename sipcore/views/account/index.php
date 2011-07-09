<?php
$this->breadcrumbs=array(
	'',
);

$this->menu=array(
	array('label'=>'Manage Accounts', 'url'=>array('admin')),
        array('label'=>'Schoolyears', 'url'=>array('schoolyear/index')),
        array('label'=>'Breaks (vacantions)', 'url'=>array('breaks/index')),
        array('label'=>'Schools', 'url'=>array('school/index')),
        array('label'=>'Classes', 'url'=>array('classes/index')),
);

$this->pageTitle = 'Contul meu';

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
                'name',
		'email',
                'phone',
                'registered:Date',

	),
)); ?>
