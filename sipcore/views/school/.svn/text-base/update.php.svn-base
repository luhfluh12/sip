<?php
$this->breadcrumbs=array(
	'Schools'=>array('index'),
	$school->name=>array('view','id'=>$school->id),
);

$this->pageTitle='Actualizare școală';

$this->menu=array(
	array('label'=>'List School', 'url'=>array('index')),
	array('label'=>'Create School', 'url'=>array('create')),
	array('label'=>'View School', 'url'=>array('view', 'id'=>$school->id)),
	array('label'=>'Manage School', 'url'=>array('admin')),
);
?>

<h1>Update School <?php echo $school->id; ?></h1>

<?php echo $this->renderPartial('_form', array('school'=>$school,'account'=>$account)); ?>