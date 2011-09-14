<?php
$this->breadcrumbs = array(
    '',
);
$this->menu = array(
    array('label' => 'Contul meu', 'url' => array('account/index')),
    array('label' => 'Setări generale', 'url' => array('account/update', 'p' => 'general')),
    array('label' => 'Schimbă parola', 'url' => array('account/update', 'p' => 'password')),
    array('label' => 'Schimbă numărul de telefon', 'url' => array('account/update', 'p' => 'phone')),
    array('label' => 'Schimbă întrebarea de securitate', 'url' => array('account/update', 'p' => 'question')),
    array('label' => 'Schimbă e-mail', 'url' => array('account/update', 'p' => 'email')),
);


$this->pageTitle = 'Contul meu';
?>
<h1>Detalii cont</h1>
<?php if (!$model->security_question): ?>
    <div class="flash-notice">
        Nu ați setat încă o întrebare de securitate.
        <?php echo CHtml::link('Vă rugăm să faceți asta acum',array('account/update','p'=>'question')); ?>.
    </div>
<?php
endif;

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        'email',
        'phone',
        'registered:Date',
        'sms_hour1',
        'sms_hour2',
    ),
));
?>
