<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'school-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($school, $account); ?>

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

	<div class="row">
		<?php echo $form->labelEx($account,'email'); ?>
		<?php echo $form->textField($account,'email',array('size'=>50,'maxlength'=>225)); ?>
		<?php echo $form->error($account,'email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($account,'phone'); ?>
		<span class="note">în format internațional (exemplu: 4074xxxxxxx)</span>
		<?php echo $form->textField($account,'phone',array('size'=>50,'maxlength'=>12)); ?>
		<?php echo $form->error($account,'phone'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Adaugă școală'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
