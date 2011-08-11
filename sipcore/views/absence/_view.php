<div class="schoolmark" id="schoolabsence_<?php echo $absence->id; ?>">
    <span class="<?php echo $absence->authorized == Absence::STATUS_UNAUTH ? 'un' : ''; ?>authorized absence">
        <?php echo date('d F Y',$absence->date); ?>
    </span>
    <?php if ($adminOptions): ?>
    <span class="menu">
        <?php echo CHtml::link('motivează', '#',array(
            'onclick'=>'javascript:return authorize_absence('.$absence->id.','.Absence::STATUS_AUTH.');',
            'style'=>'display:'.($absence->authorized==Absence::STATUS_AUTH ? 'none' : 'inline-block').';',
            'id'=>'schoolabsence_'.$absence->id.'_auth'.Absence::STATUS_AUTH,
        )); ?>
        <?php echo CHtml::link('anulează motivarea', '#',array(
            'onclick'=>'javascript:return authorize_absence('.$absence->id.','.Absence::STATUS_UNAUTH.');',
            'style'=>'display:'.($absence->authorized==Absence::STATUS_UNAUTH ? 'none' : 'inline-block').';',
            'id'=>'schoolabsence_'.$absence->id.'_auth'.Absence::STATUS_UNAUTH,
        )); ?>
        <?php echo CHtml::link('șterge', '#',array(
            'onclick'=>'javascript:return delete_absence('.$absence->id.');',
        )); ?>
    </span>
    <?php endif; ?>
</div>