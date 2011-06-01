<div class="smsform">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-ManualSmsForm-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
        <?php echo $form->textArea($model,'message'); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->