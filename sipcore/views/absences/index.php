<div style="font-weight: bold; font-size: 14px;">Absențe</div>
<?php $absences = Absences::model()->findByStudentAndSubject($student->id, $subject->id); ?>

<div id="absences_<?php echo $subject->id; ?>">
    <?php foreach ($absences as $absence): ?>
        <?php $this->renderPartial('//absences/_view',array('absence'=>$absence,'adminOptions'=>$adminOptions)); ?>
    <?php endforeach; ?>
</div>
<?php if ($adminOptions): ?>
<?php echo CHtml::link('Adaugă absență','javascript:void(0);',array(
    'onclick'=>'open_absence('.$subject->id.', 0); return false;'
)); ?>
<div id="abse_<?php echo $subject->id; ?>_0" class="schoolmarkEditer">
    <?php $this->renderPartial('//absences/_form',array(
        'student'=>$student->id,
        'subject'=>$subject->id,
        'model'=>new Absences,
    )); ?>
</div>
<?php endif; ?>
