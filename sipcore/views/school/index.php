<?php
$this->breadcrumbs=array(
	'',
);
if (Yii::app()->user->checkAccess('admin')) {
    $this->menu=array(
            array('label'=>'Adaugă o școală', 'url'=>array('create')),
            array('label'=>'Administrează școli', 'url'=>array('admin')),
    );
}
$this->pageTitle = 'Școli care folosesc SIP';
?>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
