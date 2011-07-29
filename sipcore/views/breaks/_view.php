<div class="view">

	<?php echo CHtml::link(CHtml::encode($data->name), array('update', 'id'=>$data->id), array('class'=>'title')); ?>
	<br />

	<b>Anul școlar:</b>
	<?php echo Schoolyear::thisYearName($data->start); ?>
	<br />

	<b>Perioada :</b>
	<?php echo date('j M Y',$data->start); ?> - <?php echo date('j M Y',$data->end); ?>
	<br />
</div>