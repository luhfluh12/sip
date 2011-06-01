<?php if ($data===false): ?>

<div class="flash-notice">
    Orarul nu este definit încă.
    Vă rugăm să-l completați pentru a adăuga note și absențe mai ușor.
</div>

<?php $this->renderPartial('/schedule/_form'); ?>

<?php else: ?>
<?php $this->renderPartial('/schedule/_form',array('items'=>$data)); ?>
<?php endif;?>