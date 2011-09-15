<?php
/**
 * @var $model NULL
 */
?>
<div class="authorization">
    <h1>
        Administrator de site
    </h1>
    <div>
        <?php echo CHtml::link('Școli',array('school/index'), array('class'=>'button')); ?>
        <?php echo CHtml::link('Întrebări de securitate',array('securityQuestion/index'), array('class'=>'button')); ?>
        <?php echo CHtml::link('Vacanțe',array('breaks/index'), array('class'=>'button')); ?>
        <?php echo CHtml::link('Ani școlari',array('schoolyear/index'), array('class'=>'button')); ?>
    </div>
</div>