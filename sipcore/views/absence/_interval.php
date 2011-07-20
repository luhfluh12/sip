<div style="margin:5px 5px 15px 5px;">
<?php echo CHtml::link('Adaugă un interval de absențe','#',array(
    'onclick'=>'javascript:$("#add_absences_interval").fadeToggle();return false;',
    'class'=>'markmanagerButton'
)); ?>
<?php // @todo add authorize interval feature 
//echo CHtml::link('Motivează un interval de absențe','#',array(
   // 'onclick'=>'javascript:$("#auth_absences_interval").fadeToggle();return false;',
   // 'class'=>'markmanagerButton'
//)); ?>
<?php echo CHtml::link('Notă purtare:'.$purtare ,'#',array(
    'onclick'=>'javascript:$("#nota_purtare").fadeToggle();return false;',
    'class'=>'markmanagerButton'
)); ?>
</div>



<div id="add_absences_interval" class="siptoolbox">
    <div id="marksmenu_add" class="marksmenu_sub">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->hiddenField(new Absence,'student',array('value'=>$student->id,'name'=>'student')); ?>
    <p><strong>Dată început:</strong><br />
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
        array(
            // you must specify name or model/attribute
            'name'=>'start',
            'value' => '',
            //  optional: jquery Datepicker options
            'options' => array(
                'dateFormat'=>'dd.mm.yy',
                'changeMonth' => 'false',
                'language'=>'ro',
                'changeYear' => 'false',
                'showButtonPanel' => 'false',
                'constrainInput' => 'true',
                'duration'=>'fast',
            ),
            'htmlOptions'=>array(
                'size'=>20
            ),
    ));?>
    </p><p><strong>Dată sfârșit (opțional):</strong><br />
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
        array(
            // you must specify name or model/attribute
            'name'=>'end',
            'value' => '',
            //  optional: jquery Datepicker options
            'options' => array(
                'dateFormat'=>'dd.mm.yy',
                'changeMonth' => 'false',
                'language'=>'ro',
                'changeYear' => 'false',
                'showButtonPanel' => 'false',
                'constrainInput' => 'true',
                'duration'=>'fast',
            ),
            'htmlOptions'=>array(
                'size'=>20
            ),
    ));?>
    </p>

    <?php echo CHtml::ajaxSubmitButton('adaugă absențe', array('absences/saveInterval'),array(
       'success'=>"js:function(data){
            var obj = $.parseJSON(data);
            updateAbsences(obj);
            $('#absences_int_status').html('Au fost adăugate '+obj.added+' absențe.');
           }",
       'error'=>"js:function(){
            $('#absences_int_status').html('A apărut o eroare. Vă rugăm reîncercați.');
        }",
    )); ?>
    <?php echo CHtml::link('renunță','#',array('onclick'=>'javascript:$("#add_absences_interval").fadeToggle();return false;')); ?>
    <?php $this->endWidget(); ?>
    </div>
    <div id="absences_int_status"></div>
</div>



<div id="nota_purtare" class="siptoolbox">
    <div id="marksmenu_add2" class="marksmenu_sub">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->hiddenField(new Chart,'student',array('value'=>$student->id,'name'=>'Mark[student]')); ?>
    <?php echo CHtml::dropDownList('newpurtare',$purtare,array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10)); ?>
    <?php echo CHtml::ajaxSubmitButton('modifică', array('mark/purtare'),array(
       'success'=>"js:function(data){
            $('#purtare_status').html('Nota a fost actualizata cu succes!');
           }",
       'error'=>"js:function(){
            $('#purtare_status').html('A apărut o eroare. Vă rugăm reîncercați.');
        }",
    )); ?>
    <?php echo CHtml::link('renunță','#',array('onclick'=>'javascript:$("#nota_purtare").fadeToggle();return false;')); ?>
    <?php $this->endWidget(); ?>
    </div>
    <div id="purtare_status"></div>
</div>