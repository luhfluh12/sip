<h1>Schimbă întrebarea de securitate</h1>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'account-form',
                'enableAjaxValidation' => false,
            ));
    ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'security_question'); ?>
        <?php echo $form->dropDownList($model, 'security_question', CHtml::listData(SecurityQuestion::model()->findAll(), 'id', 'question')); ?>
        <?php echo $form->error($model, 'security_question'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'security_answer'); ?>
        <?php echo $form->textField($model, 'security_answer', array('size' => 60, 'value' => '')); ?>
        <?php echo $form->error($model, 'security_answer'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Actualizează'); ?>
        <?php echo CHtml::link('Renunță', array('account/index')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->