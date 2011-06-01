<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'old_password'); ?>
		<?php echo $form->passwordField($model,'old_password',array('size'=>60)); ?>
		<?php echo $form->error($model,'old_password'); ?>
	</div>
        <h1>Schimbare e-mail</h1>
        <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>254)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

        <h1>Schimbare număr de telefon</h1>
        <span class="note">Scrieți numărul în format internațional (ex. 40745xxxxxx)</span>
        <div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

        <h1>Schimbare parolă</h1>
        <span class="note">Completați doar dacă doriți schimbarea parolei</span>
	<div class="row">
		<?php echo $form->labelEx($model,'new_password'); ?>
		<?php echo $form->passwordField($model,'new_password',array('size'=>60)); ?>
		<?php echo $form->error($model,'new_password'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'new_password2'); ?>
		<?php echo $form->passwordField($model,'new_password2',array('size'=>60)); ?>
		<?php echo $form->error($model,'new_password2'); ?>
	</div>

        
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
                <?php echo CHtml::link('Renunță',array('index')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->