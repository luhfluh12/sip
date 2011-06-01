<?php
    $this->pageTitle=Yii::app()->name;
    $this->sip_title=array(
        array('img'=>'sms.png',
            'title'=>'Alerte SMS',
            'text'=>'Parintii sunt tot timpul informati despre situatiile neplacute ale elevilor, inainte de a fi prea tarziu.'),
        array('img'=>'full.png',
            'title'=>'Situatia completa',
            'text'=>'Vezi situatia scolara completa a elevului, online, intr-un format usor de inteles.'),
        array('img'=>'stats.png',
            'title'=>'Statistici',
            'text'=>'Este usor sa observi in ce directie merge elevul cu graficele noastre lunare si semestriale.'),
    );
?>

<div class="span-17">
    <h1>Sistem de informare a părinților</h1><br /><br /><br />
    <img src="css/whatissip.jpg" alt="ce e sip?"/>
    <div class="clearfix" style="font-size:15px;max-width:550px;">
        
    </div>

</div>

<div class="span-6 last">
    <h1>Conectare</h1>

    <p>Vă rugăm să completați formularul alăturat pentru a intra în contul dvs.</p>

    <div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'login-form',
            'enableAjaxValidation'=>false,
    )); ?>
            <?php echo $form->errorSummary($model); ?>
            <div class="row">
                    <?php echo $form->labelEx($model,'email'); ?>
                    <?php echo $form->textField($model,'email'); ?>
                    <?php //echo $form->error($model,'email'); ?>
            </div>

            <div class="row">
                    <?php echo $form->labelEx($model,'password'); ?>
                    <?php echo $form->passwordField($model,'password'); ?>
                    <?php //echo $form->error($model,'password'); ?>
            </div>

            <div class="row buttons">
                    <?php echo CHtml::submitButton('Conectează-mă'); ?>
            </div>
            <p class="note">Ați uitat parola? Urmați pașii de resetare a parolei.</p>
    <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>
