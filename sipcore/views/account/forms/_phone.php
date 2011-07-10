<h1>Schimbă numărul de telefon</h1>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'account-form',
                'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'phone'); ?>
        <?php echo $form->textField($model, 'phone', array('size' => 60, 'maxlength'=>12)); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Actualizează'); ?>
        <?php echo CHtml::link('Renunță', array('account/index')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->