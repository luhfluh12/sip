<?php
    $this->pageTitle=Yii::app()->name;
    $this->sip_title=array('//layouts/headers/home');
?>

<div class="span-17">
    <h1>Sistem de informare a părinților</h1>
    <?php ?>
    <?php if(Yii::app()->user->getFlash('activate',false)!==false): ?>
        <div class="flash-success">Contul dvs. a fost activat cu succes. Acum vă puteți conecta.</div>
    <?php endif; ?>
    
    <img src="images/whatissip.jpg" alt="ce e sip?"/>
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
                    <?php echo $form->textField($model,'email', array('size'=>'25')); ?>
                    <?php //echo $form->error($model,'email'); ?>
            </div>

            <div class="row">
                    <?php echo $form->labelEx($model,'password'); ?>
                    <?php echo $form->passwordField($model,'password', array('size'=>'25')); ?>
                    <?php //echo $form->error($model,'password'); ?>
            </div>

            <div class="row buttons">
                    <?php echo CHtml::submitButton('Conectează-mă'); ?>
            </div>
            <p class="minimenu">
                <?php echo CHtml::link('Ați uitat parola? Urmați pașii de resetare a parolei',array('account/lostPassword')); ?>
                <?php echo CHtml::link('Prima dată aici? Activați-vă contul acum.',array('account/activate')); ?>
            </p>
    <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>
