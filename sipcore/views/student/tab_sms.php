<h2>Trimite un SMS manual</h2>
<?php $this->renderPartial('//sms/_form', array('model'=>new Sms)); ?>

<h2>SMS-urile primite de părintele acestui elev:</h2>

<?php if (empty($smses)): ?>
    <strong>Nici un SMS trimis încă...</strong>
<?php else:
    foreach ($smses as $sms):
        $this->renderPartial('//sms/_view',array('model'=>$sms));
    endforeach;
endif; ?>
