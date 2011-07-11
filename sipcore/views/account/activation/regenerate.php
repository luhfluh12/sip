<?php
$this->breadcrumbs = array(
    'Activare cont' => array('account/activate'),
);
$this->sip_title = "Retrimitere cod de activare";
?>
<h1>E-mail sau număr de telefon</h1>
<?php if (isset($error)): ?>
    <div class="flash-error"><?php echo $error; ?></div>
<?php endif; ?>
<div>
    <p class='note'>
        Vă rugăm introduceți numărul de telefon sau adresa de e-mail cu care sunteți înregistrat în SIP.
    </p>
</div>

<div class='form'>
    <?php
    echo CHtml::form(array('account/lostActivationCode'), 'post'),
    CHtml::label('E-mail sau telefon', 'login'),
    CHtml::textField('login', '');
    ?>
    <br /><br />
    <?php echo CHtml::submitButton('Retrimite codul de activare'),
    CHtml::endForm(); ?>
    <p class="note">Codul de activare este, de fapt, regenerat și apoi noul cod este trimis.</p>
</div>

