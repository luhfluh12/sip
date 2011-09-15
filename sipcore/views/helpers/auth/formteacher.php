<?php
/**
 * @var $model Classes
 */
?>
<div class="authorization">
    <h1>
        Diriginte al clasei <?php echo $model->grade, ' ', CHtml::encode($model->name); ?>
    </h1>
    <div>
        <?php echo CHtml::link('Administrează clasa',array('classes/view','id'=>$model->id), array('class'=>'button')); ?>
        <?php echo CHtml::link('Adaugă un elev',array('student/create','class'=>$model->id), array('class'=>'button')); ?>
    </div>
</div>