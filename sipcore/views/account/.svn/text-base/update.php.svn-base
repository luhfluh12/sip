<?php
$this->breadcrumbs=array(
	'Contul meu'=>array('index'),
	'',
);

/*$this->menu=array(
	array('label'=>'List Account', 'url'=>array('index')),
	array('label'=>'Create Account', 'url'=>array('create')),
	array('label'=>'View Account', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Account', 'url'=>array('admin')),
);*/

$this->pageTitle='Actualizare cont';
?>

<?php if ($msg = Yii::app()->user->getFlash('account_updated')): ?>
    <div class="flash-success"><?php echo $msg; ?></div>
<?php endif; ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>