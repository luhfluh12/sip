<?php
$this->breadcrumbs = array(
    'Școli' => array('school/index'),
    $student->rSchool->name . ' ' . $student->rSchool->city => array('school/view', 'id' => $student->school),
    $student->rClass->grade . ' ' . $student->rClass->grade => array('classes/view', 'id' => $student->class),
    $student->name => array('view', 'id' => $student->id),
);

$this->sip_title = 'Actualizează';
?>

<h1><?php echo $student->name; ?></h1>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'student-form',
                'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Câmpurile marcate cu <span class="required">*</span> sunt obligatorii.</p>

    <?php echo $form->errorSummary(array($student)); ?>
    <h2>Date elev</h2>
    <div class="row">
        <?php echo $form->labelEx($student, 'name'); ?>
        <?php echo $form->textField($student, 'name', array('size' => 60, 'maxlength' => 200)); ?>
        <?php echo $form->error($student, 'name'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Adaguă elev'); ?>
        <?php echo CHtml::link('renunță',array('student/view','id'=>$student->id)); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>