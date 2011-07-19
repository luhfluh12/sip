<div style="font-size:14px; font-weight:bold;">Teză</div>
<div class="mark" id="thesis_<?php echo $subject; ?>">
    <?php if ($model !== null): ?>
        Notă la teză: 
        <span style="font-size:16px; font-weight:bold;"><?php echo $model->mark; ?></span>
        <?php if ($adminOptions)
            $edit = true; ?>
    <?php else: ?>
        <span style="font-size:14px;">nu a fost adăugată</span>
        <?php
        if ($adminOptions) {
            $model = new Mark;
            $edit = false;
        }
    endif;
    ?>
</div>
<?php if ($adminOptions): ?>
    <?php
    echo CHtml::link(($edit === false ? 'Adaugă teză' : 'Modifică teza'), 'javascript:void(0);', array(
        'onclick' => 'open_mark(' . $subject . ', "thesis"); return false;'
    ));
    ?>
    <div id="subj_<?php echo $subject; ?>_thesis" style="display:none;" class="schoolmarkEditer">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
                    'enableAjaxValidation' => false,
                ));
        ?>

        <?php echo $form->hiddenField($model, 'student', array('value' => $student)); ?>
        <?php echo $form->hiddenField($model, 'subject', array('value' => $subject)); ?>
        <?php
        $options = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);
        if (!$model -> isNewRecord)
            $options[0] = 'șterge';
        ?>
        <?php echo $form->dropDownList($model, 'mark', $options, array('class' => 'chosemark', 'id' => 'subject' . $subject . '_thesis_selectbox')); ?>
        
        <?php
        echo CHtml::ajaxSubmitButton('salvează', array('mark/thesis'), array(
            'success' => "js:function(data, txtStatus){
               if (data==11) {
                   $('#thesis_error_" . $subject . "').html('A apărut o eroare.');
                   $('#thesis_error_" . $subject . "').slideDown('fast');
               } else if (data!=0) {
                   $('#thesis_" . $subject . "').html('Notă la teză: <span style=\"font-size:16px; font-weight:bold;\">'+data+'</span>');
                   $('#thesis_error_" . $subject . "').slideUp('fast');
               } else {
                  $('#thesis_" . $subject . "').html('');
                  $('#thesis_error_" . $subject . "').slideUp('fast');
               }
           }",
            'error' => "js:function(){
               $('#thesis_error_" . $subject . "').html('A apărut o eroare.');
               $('#thesis_error_" . $subject . "').slideDown('fast');
           }",
        ), array('style'=>'float:right;'));
        ?>

        <?php $this->endWidget(); ?>
        <div class="schoolmark_error" id="thesis_error_<?php echo $subject; ?>"></div>
    </div>
<?php endif; ?>