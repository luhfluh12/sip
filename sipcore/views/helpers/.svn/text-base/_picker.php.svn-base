<?php
$this->widget('zii.widgets.jui.CJuiDatePicker',
	array(
		// you must specify name or model/attribute
		'model'=>$model,
		'attribute'=>$attr,
		'value' => $model->$attr,
		//  optional: jquery Datepicker options
		'options' => array(
                    'dateFormat'=>'dd.mm.yy',
                    'changeMonth' => 'false',
                    'language'=>'ro',
                    'changeYear' => 'false',
                    'showButtonPanel' => 'false',
                    'constrainInput' => 'true',
                    //'showOn'=>'both',
                    //'buttonImage'=>'http://localhost/calendar.gif',
                    //'buttonImageOnly'=>'false',
                    'duration'=>'fast',
		),
                'htmlOptions'=>array(
                    'class'=>(isset($CSSclass) ? $CSSclass : ''),
                    'id'=>'Mark_'.$attr.(isset($appendId) ? '_'.$appendId : ''),
                    'size'=>'12',
                    'style'=>'width:73px;',
                    
                ),
	)
);
