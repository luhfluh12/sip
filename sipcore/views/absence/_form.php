<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->hiddenField($model,'student',array('value'=>$student)); ?>
<?php echo $form->hiddenField($model,'subject',array('value'=>$subject)); ?>
<strong>Data:</strong>
<?php
$this->renderPartial('//helpers/_picker',array('model'=>$model,'attr'=>'date','CSSclass'=>'markpicker','appendId'=>$subject.'_absence_'.$model->id));
?>
<?php echo $form->labelEx($model,'authorized'); ?>
<?php echo $form->checkBox($model,'authorized'); ?>
<?php echo CHtml::ajaxSubmitButton('salvează', array('absence/create'),array(
   'success'=>"js:function(data, txtStatus){
           var p = data.split('::');
           if (p[0]==1) {
            $('#absences_".$subject."').append(p[1]);
            $('#absences_error_".$subject."').css('display','none');
           } else {
            $('#absences_error_".$subject."').html(p[1]);
            $('#absences_error_".$subject."').slideDown('fast');
           }
           
       }",
   'error'=>"js:function(){
           $('#absences_error_".$subject."').html('A apărut o eroare.');
           $('#absences_error_".$subject."').slideDown('fast');
       }",
), array('style'=>'float:right;')); ?>

<?php $this->endWidget(); ?>
<div class="schoolmark_error" id="absences_error_<?php echo $subject; ?>"></div>