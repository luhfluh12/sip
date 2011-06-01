<div class="view">

	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('schoolyear')); ?>:</b>
	<?php echo $data->rSchoolyear->getName(); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start')); ?>:</b>
	<?php echo date('j M Y',$data->start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end')); ?>:</b>
	<?php echo date('j M Y',$data->end); ?>
	<br />

</div>