<?php
$this->breadcrumbs=array(
	'Școli'=>array('index'),
	'',
);

/*$this->menu=array(
	array('label'=>'List School', 'url'=>array('index')),
	array('label'=>'Create School', 'url'=>array('create')),
	array('label'=>'Update School', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete School', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage School', 'url'=>array('admin')),
);*/
$this->sip_title = $model->name.' '.$model->city;
?>

<h1>Clasele înscrise în SIP</h1>

<table width="100%" cellpadding="5" cellspacing="0"><tr>
<?php
if (!empty($class)) {
    $old = 0;
    foreach ($class as $data) {
        if ($data->grade != $old) {
            echo ($old === 0 ? '' : '</td>') .'<td style="vertical-align:top">';
            $old = $data->grade;
        }
        $this->renderPartial('/classes/_view',array('data'=>$data));
    }
} else
    echo 'nici o clasa';
?>
</tr></table>