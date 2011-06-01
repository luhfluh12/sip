<?php echo CHtml::form(array('schedule/saveAll'), 'post'); ?>
    <table cellpadding="0" cellspacing="2" width="100%"><tr>
    <?php for ($i=1;$i<=5;$i++): ?>
        <td style="vertical-align: top;">
            <h2>
            <?php echo Schedule::getWeekday($i); ?>
            </h2>
            <?php for ($j=1;$j<=7;$j++): ?>
            <div>
            <?php
            //echo CHtml::textField("newHour[$i][$j]", (isset($items[$i][$j]) ? $items[$i][$j] : ''), array('size'=>15));
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name'=>"newHour[$i][$j]",
                'value'=>(isset($items[$i][$j]) ? $items[$i][$j] : ''),
                'source'=>$this->createUrl('subject/hint'),
                // additional javascript options for the autocomplete plugin
                'options'=>array(
                    'showAnim'=>'fade',
                ),
                'htmlOptions'=>array(
                    'size'=>15,
                ),
            ));
            ?></div>
            <?php endfor; ?>
        </td>
    <?php endfor;?>
    </tr></table>
<?php echo CHtml::hiddenField('class', $_GET['id']); ?>
<?php echo CHtml::submitButton('Salvează orarul'); ?>
<?php echo CHtml::endForm(); ?>
