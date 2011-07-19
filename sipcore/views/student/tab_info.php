<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$student,
	'attributes'=>array(
            'name:text:Nume elev',
            'rParent.name:text:Părinte',
            'rParent.phone:text:Telefon părinte',
            'rParent.email:text:E-mail părinte',
	),
)); ?>
