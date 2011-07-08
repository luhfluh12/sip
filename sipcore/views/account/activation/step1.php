<div>
    <p class='note'>
        Vă rugăm introduceți codul de activare primit prin e-mail sau SMS.
        <?php echo CHtml::link('Am pierdut codul!', array('account/lostActivationCode')); ?>
    </p>
</div>

<div class='form'>
    <?php echo CHtml::form(array('account/activate'), 'get'),
    CHtml::label('Cod de activare', 'code'),
    CHtml::textField('code', ''); ?>
    <br /><br />
    <?php echo CHtml::submitButton('Activează-mi contul'),
    CHtml::endForm(); ?>
</div>