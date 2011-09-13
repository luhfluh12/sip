<?php

/**
 * @var $this ClassesController
 * @var $school School
 * @var $class Classes
 * @var $account Account
 */
$this->breadcrumbs = array(
    $school->name.' '.$school->city => array('school/view', 'id' => $school->id),
    ''
);

$this->pageTitle = 'Adaugă o clasă';
if (Yii::app()->user->getFlash('noClasses', false) === true): ?>
    <div class="flash-notice">Nu este înscrisă nici o clasă din această școală. Adăugați o clasă completând formularul de mai jos.</div>
<?php endif; ?>

<?php echo $this->renderPartial('_form', array('class' => $class, 'account' => $account)); ?>