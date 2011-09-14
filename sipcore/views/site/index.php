<?php
/**
 * @var $this SiteController
 */
$this->pageTitle = Yii::app()->name;
$this->sip_title = array('//layouts/headers/home');
?>
<div class="span-6">
    <h1>Contul meu</h1>
    <?php if (!Yii::app()->user->model()->security_question): ?>
        <div class="flash-notice">
            Nu ați setat o întrebare de securitate.
            <?php echo CHtml::link('Setați acum', array('account/update', 'p' => 'question')); ?>
        </div>
    <?php endif; ?>
    <?php if (!Yii::app()->user->model()->name): ?>
        <div class="flash-notice">
            Nu v-ați scris încă numele complet.
            <?php echo CHtml::link('Completați-l acum', array('account/update', 'p' => 'general')); ?>
        </div>
    <?php endif; ?>
    <p>
        <strong>Adresă e-mail</strong><br />
        <?php echo CHtml::encode(Yii::app()->user->model()->email); ?>
    </p>
    <p>
        <strong>Număr de telefon</strong><br />
        <?php echo CHtml::encode(Yii::app()->user->model()->phone); ?>
    </p>
    <?php echo CHtml::link('Actualizare cont', array('account/update'), array('class' => 'button')); ?>
</div>

    <?php foreach ($authorizations as $auth) {
        $this->renderPartial('//helpers/auth/'.$auth->action,array('model'=>$auth->getModelByAction()));
    } ?>

