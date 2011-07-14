<h1>Schimbă e-mail-ul</h1>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'account-form',
                'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 254)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Actualizează'); ?>
        <?php echo CHtml::link('Renunță', array('account/index')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->