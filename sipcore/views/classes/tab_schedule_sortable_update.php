<table cellpadding="0" cellspacing="2" width="100%"><tr>
    <?php $weekday = false; $items = array();
    foreach ($data as $item): ?>
        <?php if ($item->weekday !== $weekday): ?>
            <?php if (!empty($items)) {
                $this->renderPartial('/schedule/_sortable',array('items'=>$items));
                $items=array();
            } ?>
            <?php echo ($weekday===false ? '' : '</td>');
            if ($weekday===false)
                $weekday=1;
            else
                $weekday++;
            while ($weekday < $item->weekday): ?>
                <td style="vertical-align:top;">
                    <h2><?php echo Schedule::getWeekday($weekday); ?></h2>
                </td>
                <?php $weekday++;
            endwhile; ?>
            <td style="vertical-align: top;">
            <h2><?php echo Schedule::getWeekday($weekday); ?></h2>
        <?php endif; ?>
        <?php
            $items['w'.$item->weekday.'h'.$item->hour]=$this->renderPartial('/schedule/_view',array('data'=>$item),true);
            $weekday=$item->weekday;
            ?>
    <?php endforeach; ?>
    <?php if (!empty($items)) {
        $this->renderPartial('/schedule/_sortable',array('items'=>$items));
        $items=array();
    } ?>
</td></tr></table>