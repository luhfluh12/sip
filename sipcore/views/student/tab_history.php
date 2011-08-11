<?php
$schoolyear = 0;
?>
<?php foreach ($student->rAverages as $average): ?>
    <?php if ($schoolyear != $average->year): ?>
        <h2>Anul școlar <?php echo $schoolyear; ?> - <?php echo ($schoolyear + 1); ?></h2>
    <?php endif; ?>
    <strong><?php echo $average->rSubject->name; ?></strong>
    
    <?php echo $average->sem1 ?>
<?php endforeach; ?>

