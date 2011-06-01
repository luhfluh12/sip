<div class="view">

	<?php echo CHtml::link('An școlar '.$data->getName(), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start')); ?>:</b>
	<?php echo date('j M Y',$data->start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('change')); ?>:</b>
	<?php echo date('j M Y',$data->change); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end')); ?>:</b>
	<?php echo date('j M Y',$data->end); ?>
	<br />


</div>