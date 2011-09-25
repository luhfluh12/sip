<?php
$this->pageTitle = Yii::app()->name . ' - Contactează-ne';
$this->breadcrumbs = array(
    '',
);
$this->sip_title = 'Contactează-ne';
?>

<h1>Formular de contact</h1>
<?php if (Yii::app()->user->getFlash('contact')): ?>

<?php else: ?>
    <p>
        Ne puteți contacta completând următorul formular sau puteți să ne trimiteți un e-mail la <a href="mailto:contact@siponline.ro">contact@siponline.ro</a>. Vă mulțumim!
    </p>
    <div class="form">
        <?php $form = $this->beginWidget('CActiveForm'); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="span-11 last">
            <div class="row">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 52)); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', array('size' => 52)); ?>
            </div>

            <?php
            echo CHtml::activeLabel($model, 'verifyCode');
            $this->widget('ext.recaptcha.EReCaptcha', array(
                'model' => $model,
                'attribute' => 'verifyCode',
                'theme' => 'verifyCode',
            ));
            echo CHtml::error($model, 'verifyCode');
            ?>
        </div>
        <div class="span-11">
            <div class="row">
                <?php echo $form->labelEx($model, 'subject'); ?>
                <?php echo $form->textField($model, 'subject', array('size' => 52, 'maxlength' => 128)); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'body'); ?>
                <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
            </div>
            <div class="row buttons">
                <?php echo CHtml::submitButton('Trimite mesajul'); ?>
            </div>
        </div>




        <?php $this->endWidget(); ?>

    </div><!-- form -->
<?php endif; ?>


