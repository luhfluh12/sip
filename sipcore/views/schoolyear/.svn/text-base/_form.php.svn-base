<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'schoolyear-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'start'); ?>
		<?php $this->renderPartial('//helpers/_picker',array('model'=>$model,'attr'=>'start')); ?>
		<?php echo $form->error($model,'start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'change'); ?>
		<?php $this->renderPartial('//helpers/_picker',array('model'=>$model,'attr'=>'change')); ?>
		<?php echo $form->error($model,'change'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end'); ?>
		<?php $this->renderPartial('//helpers/_picker',array('model'=>$model,'attr'=>'end')); ?>
		<?php echo $form->error($model,'end'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->