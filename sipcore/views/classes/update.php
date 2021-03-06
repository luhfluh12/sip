<?php
$this->breadcrumbs = array(
    $class->rSchool->name . ' ' . $class->rSchool->city => array('school/view', 'id' => $class->school),
    $class->grade . ' ' . $class->name => array('view', 'id' => $class->id),
    '',
);

$this->pageTitle = 'Actualizare clasă (admin)';
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'classes-form',
                'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary(array($class)); ?>
    <div class="row">
        <?php echo $form->labelEx($class, 'school'); ?>
        <?php echo $form->dropDownList($class, 'school', CHtml::listData(School::model()->findAll(), 'id', 'name')); ?>
        <?php echo $form->error($class, 'school'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($class, 'grade'); ?>
        <?php echo $form->textField($class, 'grade'); ?>
        <?php echo $form->error($class, 'grade'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($class, 'name'); ?>
        <?php echo $form->textField($class, 'name', array('size' => 10, 'maxlength' => 10)); ?>
        <?php echo $form->error($class, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($class, 'profile'); ?>
        <?php echo $form->textField($class, 'profile', array('size' => 60, 'maxlength' => 150)); ?>
        <?php echo $form->error($class, 'profile'); ?>
    </div>

    <h1>Limite</h1>
    <div class="row">
        <?php echo $form->labelEx($class, 'students'); ?>
        <?php echo $form->textField($class, 'students', array('size' => 60, 'maxlength' => 2)); ?>
        <?php echo $form->error($class, 'students'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($class, 'payment'); ?>
        <?php echo $form->textField($class, 'payment', array('size' => 60, 'maxlength' => 30)); ?>
        <?php echo $form->error($class, 'payment'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Salvează modificările'), ' ',
        CHtml::link('renunță', array('classes/view', 'id' => $class->id)); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->