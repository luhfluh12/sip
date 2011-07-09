<?php
$this->breadcrumbs=array('Contul meu'=>array('account/index'));

$this->menu = array(
    array('label'=>'Contul meu','url'=>array('account/index')),
    array('label'=>'Setări generale', 'url'=>array('account/update','p'=>'general')),
    array('label'=>'Schimbă parola', 'url'=>array('account/update','p'=>'password')),
    array('label'=>'Schimbă numărul de telefon', 'url'=>array('account/update','p'=>'phone')),
    array('label'=>'Schimbă întrebarea de securitate', 'url'=>array('account/update','p'=>'question')),
    array('label'=>'Schimbă e-mail', 'url'=>array('account/update','p'=>'email')),
);

$this->pageTitle='Actualizare cont';
?>

<?php if (Yii::app()->user->hasFlash('account_updated')): ?>
    <div class="flash-success"><?php echo Yii::app()->user->getFlash('account_updated'); ?></div>
<?php endif; ?>

<?php echo $this->renderPartial('forms/_'.$p, array('model'=>$model)); ?>