<h1>Setări generale</h1>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'account-form',
                'enableAjaxValidation' => false,
            ));
    ?>
    <?php 
    echo $form->errorSummary($model);
    $hours = array();
    for ($i=0;$i<=23;$i++) $hours[$i]=$i;
    ?>


    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength'=>50)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <strong>Vreau să primesc SMS-uri în zilele lucrătoare, între orele:</strong><br />
        <?php echo $form->dropDownList($model, 'sms_hour1', $hours); ?>
        <?php echo $form->error($model, 'sms_hour1'); ?>
        <strong>și</strong>
        <?php echo $form->dropDownList($model, 'sms_hour2', $hours); ?>
        <?php echo $form->error($model, 'sms_hour2'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Actualizează'); ?>
        <?php echo CHtml::link('Renunță', array('account/index')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->