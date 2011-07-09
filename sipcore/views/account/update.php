<?php
$this->breadcrumbs=array('Contul meu'=>array('account/index'));

$this->pageTitle='Actualizare cont';
?>

<?php if (Yii::app()->user->hasFlash('account_updated')): ?>
    <div class="flash-success"><?php Yii::app()->user->getFlash('account_updated'); ?></div>
<?php endif; ?>

<?php echo $this->renderPartial('forms/_'.$p, array('model'=>$model)); ?>