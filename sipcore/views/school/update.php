<?php
$this->breadcrumbs=array(
	'Schools'=>array('index'),
	$school->name=>array('view','id'=>$school->id),
);

$this->pageTitle='Actualizare școala';

?>

<h1><?php echo $school->name; ?> <?php echo $school->city; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'school-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Câmpurile marcate cu <span class="required">*</span> sunt obligatorii.</p>

	<?php echo $form->errorSummary($school); ?>

	<div class="row">
		<?php echo $form->labelEx($school,'name'); ?>
		<?php echo $form->textField($school,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($school,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($school,'city'); ?>
		<?php echo $form->textField($school,'city',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($school,'city'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Actualizează informații'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
