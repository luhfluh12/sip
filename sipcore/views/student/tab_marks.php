<?php if (empty($subjects)): ?>
    <div class="flash-notice">Nu putem genera materiile pentru că orarul clasei nu este definit.</div>
<?php else: ?>
    <?php if ($adminOptions): ?>
        <div class="marksmenu">
            <?php $this->renderPartial('//absence/_interval', array('student' => $student, 'purtare' => $purtare)); ?>
        </div>
    <?php endif; ?>
    <?php foreach ($subjects as $subject): ?>
        <?php if ($subject->show == 1): ?>
            <h3 style="margin:0;padding:3px;color:#BD2600"><?php echo $subject->name; ?></h3>
            <table width="100%" style="border-bottom:1px solid #555;">
                <tr>
                    <td style="vertical-align: top;width:34%;">
                        <?php $this->renderPartial('//mark/index', array('subject' => $subject, 'student' => $student, 'adminOptions' => $adminOptions)); ?>
                    </td>
                    <td style="vertical-align: top;width:23%;">
                        <?php $this->renderPartial('//mark/_thesis', array('model' => Mark::model()->getCurrentThesis($student->id, $subject->id),
                            'subject' => $subject->id, 'student' => $student->id, 'adminOptions' => $adminOptions)); ?>
                    </td>
                    <td style="vertical-align: top;width:43%;">
                        <?php $this->renderPartial('//absence/index', array('subject' => $subject, 'student' => $student, 'adminOptions' => $adminOptions)); ?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>