<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'classes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Câmpurile marcate cu <span class="required">*</span> sunt obligatorii.</p>

	<?php echo $form->errorSummary(array($class,$account)); ?>

	<div class="row">
		<?php echo $form->labelEx($class,'school'); ?>
		<?php echo $form->dropDownList($class,'school',CHtml::listData(School::model()->findAll(), 'id', 'name')); ?>
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

        <p class="note">Dacă numărul de telefon este deja folosit, noile atribuții vor fi adăugate acelui cont.</p>
        
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
        
        <h1>Limite</h1>
        <div class="row">
		<?php echo $form->labelEx($class,'students'); ?>
		<?php echo $form->textField($class,'students',array('size'=>60,'maxlength'=>2)); ?>
		<?php echo $form->error($class,'students'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($class,'payment'); ?>
		<?php echo $form->textField($class,'payment',array('size'=>60,'maxlength'=>30)); ?>
		<?php echo $form->error($class,'payment'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Adaugă clasă'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->