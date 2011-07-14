<h1>Schimbă parola</h1>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'account-form',
                'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'old_password'); ?>
        <?php echo $form->passwordField($model, 'old_password', array('size' => 60)); ?>
        <?php echo $form->error($model, 'old_password'); ?>
    </div>
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

    <div class="row buttons">
        <?php echo CHtml::submitButton('Schimbă parola'); ?>
        <?php echo CHtml::link('Renunță', array('account/index')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->