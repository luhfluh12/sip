<?php
$form = $this->beginWidget('CActiveForm', array(
            'enableAjaxValidation' => false,
        ));
?>

<?php echo $form->hiddenField($model, 'student', array('value' => $student)); ?>
<?php echo $form->hiddenField($model, 'subject', array('value' => $subject)); ?>

<strong>Nota:</strong>
<?php echo $form->dropDownList($model, 'mark', array(10 => 10, 9 => 9, 8 => 8, 7 => 7, 6 => 6, 5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1), array('class' => 'chosemark')); ?>
<strong>Data:</strong>
<?php
$this->renderPartial('//helpers/_picker', array('model' => $model, 'attr' => 'date', 'CSSclass' => 'markpicker', 'appendId' => $subject . '_' . $model->id));
?>
<?php
echo CHtml::ajaxSubmitButton('salvează', array('mark/create'), array(
    'success' => "js:function(data, txtStatus){
           var p = data.split('::');
           if (p[0]==1) {
            $('#marks_" . $subject . "').append(p[1]);
            $('#marks_error_" . $subject . "').css('display','none');
           } else {
            $('#marks_error_" . $subject . "').html(p[1]);
            $('#marks_error_" . $subject . "').slideDown('fast');
           }
           
       }",
    'error' => "js:function(){
           $('#marks_error_" . $subject . "').html('A apărut o eroare.');
           $('#marks_error_" . $subject . "').slideDown('fast');
       }",
        ), array('style' => 'float:right;'));
?>

<?php $this->endWidget(); ?>
<div class="schoolmark_error" id="marks_error_<?php echo $subject; ?>"></div>