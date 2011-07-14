<div class="flash-success">
    Contul dvs. SIP a fost activat și acum vă puteți conecta, dar vă recomandăm să parcurgeți și acest ultim pas.
</div>

<div>
    <p class='note'>
        SIP trimite mesaje SMS de avertizare automat atunci când performanțele copilului dvs. la școală scad.
        De asemenea, diriginții au posibilitatea de a vă trimite mesaje pentru a anunța ședințe sau alte evenimente.<br /><br />
        <?php echo CHtml::link('Cum și când sunt trimise mesajele?', array('help/post/read','id'=>4)); ?><br /><br />
        Vă rugăm să alegeți orele între care preferați să primiți aceste mesaje.
        
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


    <div class="row buttons">
        <?php echo CHtml::submitButton('Salvează și mergi mai departe'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>