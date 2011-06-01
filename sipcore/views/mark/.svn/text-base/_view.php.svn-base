<div class="schoolmark" id="schoolmark_<?php echo $mark->id; ?>">
    <span class="mark">
        <?php echo $mark->mark; ?>
    </span>
    -
    <span class='date'>
        <?php echo date('d F Y',$mark->date); ?>
    </span>
    <?php if ($adminOptions): ?>
    <span class="menu">
        <?php echo CHtml::link('șterge', '#',array(
            'onclick'=>'javascript:return delete_mark('.$mark->id.');',
        )); ?>
    </span>
    <?php endif; ?>
</div>