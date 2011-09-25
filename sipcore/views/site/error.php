<?php
$this->pageTitle=Yii::app()->name . ' - Eroare';
$this->breadcrumbs=array(
	'Eroare',
);
$this->sip_title = 'Eroare '.$code;
?>

<div>
    <?php if (!$message): ?>
        Ne cerem scuze pentru eroare apărută.
    <?php else: ?>
        <?php echo $message; ?>
    <?php endif; ?>
</div>



<div class="error">
<?php
    if (Yii::app()->user->checkAccess('admin')) {
 //       echo CHtml::encode($message);
    }
?>
</div>