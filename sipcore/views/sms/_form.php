<div class="smsform">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'sms-ManualSmsForm-form',
                'enableAjaxValidation' => false,
            ));
    ?>
    <?php echo $form->textArea($model, 'message', array('rows' => 1, 'cols' => 60)); ?>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Trimite mesajul'); ?>
        <span>Mesajul nu mai poate fi modificat și/sau revăzut.</span>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->