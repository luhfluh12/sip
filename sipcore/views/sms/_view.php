<div class="archive">
    <div class="<?php echo ($model->status == Sms::STATUS_SENDING || $model->status == Sms::STATUS_QUEUE ? "active" : "inactive"); ?>">
        <?php if ($model->status == Sms::STATUS_SENT): ?>
            Trimis la data de <?php echo date('d F Y', $model->sent); ?>, la numărul <?php echo $model->to; ?>
        <?php else: ?>
            În curs de trimitere...
        <?php endif; ?>

    </div>
    <?php echo $model->message; ?>
</div>