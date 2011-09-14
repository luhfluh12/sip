<?php
$this->breadcrumbs = array(
    '',
);

$this->sip_title = CHtml::encode($model->name . ' ' . $model->city);
?>

<div class="inpagemenu">
    <?php echo CHtml::link('Adaugă o clasă', array('classes/create','school'=>$model->id), array('class'=>'button')); ?>
</div>

<h1>Clasele înscrise în SIP</h1>

<table width="100%" cellpadding="5" cellspacing="0"><tr>
    <?php
    $old = 0;
    foreach ($class as $data) {
        if ($data->grade != $old) {
            echo ($old === 0 ? '' : '</td>'), '<td style="vertical-align:top">';
            $old = $data->grade;
        }
        $this->renderPartial('/classes/_view', array('data' => $data));
    }
    ?>
</tr></table>    