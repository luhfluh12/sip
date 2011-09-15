<?php
/**
 * @var $model School
 */
?>
<div class="authorization">
    <h1>
        <?php echo CHtml::encode($model->name),' ',CHtml::encode($model->city); ?>
    </h1>
    <div>
        <?php echo CHtml::link('Administrează școala',array('school/view','id'=>$model->id), array('class'=>'button')); ?>
        <?php echo CHtml::link('Adaugă o clasă',array('classes/create','school'=>$model->id), array('class'=>'button')); ?>
    </div>
</div>