<div style="font-weight: bold; font-size: 14px;">Note</div>
<?php $marks = Mark::model()->findByStudentAndSubject($student->id, $subject->id); ?>

<div id="marks_<?php echo $subject->id; ?>">
    <?php
    foreach ($marks as $mark) {
        $this->renderPartial('//mark/_view', array('mark' => $mark, 'adminOptions' => $adminOptions));
    }
    ?>
</div>
<?php if ($adminOptions): ?>
    <?php
    echo CHtml::link('Adaugă notă și dată', 'javascript:void(0);', array(
        'onclick' => 'open_mark(' . $subject->id . ', 0); return false;'
    ));
    ?>
    <div id="subj_<?php echo $subject->id; ?>_0" class="schoolmarkEditer">
        <?php
        $this->renderPartial('//mark/_form', array(
            'student' => $student->id,
            'subject' => $subject->id,
            'model' => new Mark,
        ));
        ?>
    </div>
<?php endif; ?>

