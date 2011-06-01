<?php if (empty($this->sip_title)): ?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
    )); ?>
    <h1><?php echo $this->pageTitle; ?></h1>
<?php elseif (is_array($this->sip_title)): ?>
    <?php foreach ($this->sip_title as $item): ?>
    <div class="span-4 panel" style="background-image:url(<?php echo Yii::app()->request->baseUrl.'/images/'.$item['img']; ?>);">
        <h3>
        <?php echo $item['title']; ?>
        </h3>
        <?php echo $item['text']; ?>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
    )); ?>
    <h1><?php echo $this->sip_title; ?></h1>
<?php endif; ?>

