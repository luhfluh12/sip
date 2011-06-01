<?php
$this->pageTitle = $school->name.' '.$school->city;
$this->menu=array(
	array('label'=>'Schimbă mail sau parolă', 'url'=>array('update','id'=>Yii::app()->user->id)),
);



?>

<table width="100%" celpadding="5" cellspacing="0"><tr>
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
