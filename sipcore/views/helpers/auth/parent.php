<?php
/**
 * @var $model Student
 */
?>
<div class="authorization">
    <h1>
        Părinte al elevului <?php echo CHtml::encode($model->name); ?>
    </h1>
    <div>
        <?php echo CHtml::link('Vezi situația școlară',array('student/view','id'=>$model->id), array('class'=>'button')); ?>
    </div>
</div>