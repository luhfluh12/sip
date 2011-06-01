<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'classes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary(array($class,$teacher,$account)); ?>

	<div class="row">
		<?php echo $form->labelEx($class,'school'); ?>
		<?php echo $form->dropDownList($class,'school',School::getList()); ?>
		<?php echo $form->error($class,'school'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($class,'grade'); ?>
		<?php echo $form->textField($class,'grade'); ?>
		<?php echo $form->error($class,'grade'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($class,'name'); ?>
		<?php echo $form->textField($class,'name',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($class,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($class,'profile'); ?>
		<?php echo $form->textField($class,'profile',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($class,'profile'); ?>
	</div>
        
        <h1>Despre diriginte</h1>
        
	<div class="row">
		<?php echo $form->labelEx($teacher,'name'); ?>
		<?php echo $form->textField($teacher,'name',array('size'=>60,'maxlength'=>50)); ?>
		<?php echo $form->error($teacher,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($account,'email'); ?>
		<?php echo $form->textField($account,'email',array('size'=>60,'maxlength'=>254)); ?>
		<?php echo $form->error($account,'email'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($account,'phone'); ?>
		<?php echo $form->textField($account,'phone',array('size'=>50,'maxlength'=>12)); ?>
		<?php echo $form->error($account,'phone'); ?>
	</div>
        
        <h1>Limitări</h1>
        <div class="row">
		<?php echo $form->labelEx($class,'students'); ?>
		<?php echo $form->textField($class,'students',array('size'=>60,'maxlength'=>2)); ?>
		<?php echo $form->error($class,'students'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($class,'payment'); ?>
		<?php echo $form->textField($class,'payment',array('size'=>60,'maxlength'=>10)); ?>
		<?php echo $form->error($class,'payment'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($class->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->