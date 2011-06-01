<?php echo CHtml::link('Recalcuează rapoartele',array('classes/statistics','id'=>$classId,'recalculate'=>1));?>

<?php foreach ($statistics as $stat => $info): ?>
    <div style="padding:5px;">
        <strong><?php echo $info['text']; ?></strong>:
        <?php echo $info['value']; ?>
    </div>
<?php endforeach; ?>