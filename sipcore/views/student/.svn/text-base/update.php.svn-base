<?php
$this->breadcrumbs=array(
	'Students'=>array('index'),
	$student->name=>array('view','id'=>$student->id),
);

$this->menu=array(
	array('label'=>'List Student', 'url'=>array('index')),
	array('label'=>'Create Student', 'url'=>array('create')),
	array('label'=>'View Student', 'url'=>array('view', 'id'=>$student->id)),
	array('label'=>'Manage Student', 'url'=>array('admin')),
);
$this->sip_title='Actualizează';
?>

<h1><?php echo $student->name; ?></h1>

<?php echo $this->renderPartial('_form', array(
                    'student'=>$student,
                    'parent'=>$parent,
                    'sAccount'=>$sAccount,
                    'pAccount'=>$pAccount,
		)); ?>