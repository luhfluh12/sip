<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
    <?php if (isset($this->menu) && is_array($this->menu)): ?>
	<div class="span-18">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
    
	<div class="span-5 last">
		<div id="sidebar">
        		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	</div>
    <?php else: ?>
        <div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
        
    <?php endif; ?>
</div>
<?php $this->endContent(); ?>