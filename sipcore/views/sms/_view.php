<div style="border:1px solid #999;margin:5px;padding:3px;border-radius:3px;">
    <div style="padding:5px;background-color:<?php
if ($model->status == Sms::STATUS_SENDING || $model->status == Sms::STATUS_QUEUE)
    echo '#eee';
else
    echo '#cfc';
?>;border-radius:3px;font-weight:bold;">
        <?php if ($model->status == Sms::STATUS_SENT): ?>
            Trimis la data de <?php echo date('d F Y', $model->sent); ?>, la numărul <?php echo $model->to; ?>
        <?php else: ?>
            În curs de trimitere...
        <?php endif; ?>

    </div>
    <?php echo $model->message; ?>
</div>