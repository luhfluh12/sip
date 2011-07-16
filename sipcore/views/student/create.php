<?php
$this->breadcrumbs=array(
    'Școli'=>array('school/index'),
    $class->rSchool->name=>array('school/view','id'=>$class->rSchool->id),
    $class->grade.' '.$class->name=>array('classes/view','id'=>$class->id),
    '',
);

$this->menu=array(
	array('label'=>'Listă elevi', 'url'=>array('index'),'visible'=>Yii::app()->user->checkAccess('admin')),
	array('label'=>'Administrare elevi', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
        array('label'=>'Clasa '.$class->grade.' '.$class->name, 'url'=>array('classes/view','id'=>$class->id)),
);

$this->pageTitle = 'Adaugă elev';

if (Yii::app()->user->hasFlash('addstudent_success')): ?>
    <div class="flash-success"><?php echo Yii::app()->user->getFlash('addstudent_success'); ?></div>
<?php elseif (Yii::app()->user->hasFlash('addstudent_failure')): ?>
    <div class="flash-error"><?php echo Yii::app()->user->getFlash('addstudent_failure'); ?></div>
<?php endif; ?>    

<h1>Adaugă un elev în clasa <?php echo $class->grade.' '.$class->name; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Câmpurile marcate cu <span class="required">*</span> sunt obligatorii.</p>

	<?php echo $form->errorSummary(array($student, $account)); ?>
        <h2>Date elev</h2>
	<div class="row">
		<?php echo $form->labelEx($student,'name'); ?>
		<?php echo $form->textField($student,'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($student,'name'); ?>
	</div>

        <h2>Despre părinte</h2>

        <div class="row">
		<?php echo $form->labelEx($account,'email'); ?>
		<?php echo $form->textField($account,'email',array('size'=>60,'maxlength'=>254)); ?>
		<?php echo $form->error($account,'email'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($account,'phone'); ?>
		<?php echo $form->textField($account,'phone',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($account,'phone'); ?>
	</div>
        
	<div class="row buttons">
		<?php echo CHtml::submitButton('Adaguă elev'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->