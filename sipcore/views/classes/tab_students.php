<?php 
if (Yii::app()->user->checkAccess('admin') || Account::model()->findByPk(Yii::app()->user->id)->type==Account::TYPE_TEACHER) {
    echo CHtml::link('Add student',array('student/create','class'=>$class->id)).' - ';
}
if (Yii::app()->user->checkAccess('admin')) {
    echo CHtml::link('Edit class',array('classes/update','id'=>$class->id));
} ?>
<?php $flash=Yii::app()->user->getFlash('contact');
if ($flash=='2'):?>
    <div class="flash-success">Orarul a fost salvat cu succes.</div>
<?php elseif ($flash=='3'): ?>
    <div class="flash-error">A apărut o eroare la salvarea orarului.</div>
<?php endif; ?>

<table cellpadding="0" cellspacing="2"><tr><td style="vertical-align: top">
    <?php
    $i=1;
    $half = (int) ceil($count/2) + 1;
    $letter = ''; $change=false;
    foreach ($students as $student) {
        if ($i===$half) { $change=true; }
        if (mb_substr($student->name, 0, 1, "UTF-8") !== $letter) {
            $letter=mb_substr($student->name, 0, 1, "UTF-8");
            if ($change===true) { echo '</td><td style="vertical-align:top">'; $change=false; }
            echo ($letter==='' ? '' : '</div></div>'). '<div class="letter_list_items"><div class="letter">'.$letter.'</div><div class="items">';
        }
        $this->renderPartial('/student/_view',array('data'=>$student));
        $i++;
    }
    ?>
</td></tr></table>