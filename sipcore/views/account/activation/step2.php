<div>
    <p class='note'>
        O parolă puternică este formată din litere majuscule și minuscule, cifre și simboluri.
        <?php echo CHtml::link('Află mai multe', array('help/post/read','id'=>1)); ?>
    </p>
</div>

<div class='form'>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'account-form',
                'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'new_password'); ?>
        <?php echo $form->passwordField($model, 'new_password', array('size' => 60)); ?>
        <?php echo $form->error($model, 'new_password'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'new_password2'); ?>
        <?php echo $form->passwordField($model, 'new_password2', array('size' => 60)); ?>
        <?php echo $form->error($model, 'new_password2'); ?>
    </div>
    <?php echo CHtml::hiddenField('code', $code); ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Activează contul'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>