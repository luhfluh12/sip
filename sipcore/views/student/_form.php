<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Câmpurile marcate cu <span class="required">*</span> sunt obligatorii.</p>

	<?php echo $form->errorSummary(array($student, $parent, /*$sAccount,*/ $pAccount)); ?>
        <h2>Date elev</h2>
	<div class="row">
		<?php echo $form->labelEx($student,'name'); ?>
		<?php echo $form->textField($student,'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($student,'name'); ?>
	</div>

        <div class="row">
		<?php //echo $form->labelEx($sAccount,'email'); ?>
		<?php //echo $form->textField($sAccount,'email',array('size'=>60,'maxlength'=>254,'name'=>'sAccount[email]')); ?>
		<?php //echo $form->error($sAccount,'email'); ?>
	</div>

        <h2>Despre părinte</h2>

	<div class="row">
		<?php echo $form->labelEx($parent,'name'); ?>
		<?php echo $form->textField($parent,'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($parent,'name'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($pAccount,'email'); ?>
		<?php echo $form->textField($pAccount,'email',array('size'=>60,'maxlength'=>254,'name'=>'pAccount[email]')); ?>
		<?php echo $form->error($pAccount,'email'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($pAccount,'phone'); ?>
		<?php echo $form->textField($pAccount,'phone',array('size'=>12,'maxlength'=>12,'name'=>'pAccount[phone]')); ?>
		<?php echo $form->error($pAccount,'phone'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($parent,'related'); ?>
		<?php echo $form->textField($parent,'related',array('size'=>60,'maxlength'=>50)); ?>
		<?php echo $form->error($parent,'related'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($parent,'adress'); ?>
		<?php echo $form->textField($parent,'adress',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($parent,'adress'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($student->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->