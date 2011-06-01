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

<div><strong>E-mail</strong> : <?php echo $model->email; ?></div>
<div><strong>Tip cont</strong> : <?php echo Account::getAccountTypes($model->type); ?></div>
<?php echo CHtml::link('Schimbă e-mail sau parolă',array('account/update','id'=>$model->id)); ?>