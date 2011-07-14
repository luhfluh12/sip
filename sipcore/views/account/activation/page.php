<?php
$steps = array(
    1 => 'Codul de activare',
    2 => 'Alegeți o parolă',
);
$this->sip_title = "Activare cont";
?>
<div class="span-5" style="margin-right:20px;">
    <?php foreach ($steps as $s => $desc): ?>
        <div class="step-general step-<?php
    if ($s === $step) {
        echo 'active';
    } elseif ($s > $step) {
        echo 'coming';
    } else {
        echo 'done';
    }
        ?>">
                 <?php if ($s === $step): ?>
                <div class="arrow"></div>
            <?php endif; ?>
            <div>Pasul <?php echo $s; ?></div>
            <span><?php echo $desc; ?></span>
        </div>
    <?php endforeach; ?>

</div>

<div class="span-17 last">
    <h1><?php echo $steps[$step]; ?></h1>
    <?php if (isset($error)): ?>
        <div class="flash-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('resent')): ?>
        <div class="flash-success"><?php echo Yii::app()->user->getFlash('resent'); ?></div>
    <?php endif; ?>
    <?php $this->renderPartial('activation/step' . $step, isset($model) ? array('model' => $model, 'code' => $code) : array()); ?>
</div>

