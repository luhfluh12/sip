<?php
$this->pageTitle = Yii::app()->name;
$this->sip_title = array(
    array('img' => 'sms.png',
        'title' => 'Alerte SMS',
        'text' => 'Parintii sunt tot timpul informati despre situatiile neplacute ale elevilor, inainte de a fi prea tarziu.'),
    array('img' => 'full.png',
        'title' => 'Situatia completa',
        'text' => 'Vezi situatia scolara completa a elevului, online, intr-un format usor de inteles.'),
    array('img' => 'stats.png',
        'title' => 'Statistici',
        'text' => 'Este usor sa observi in ce directie merge elevul cu graficele noastre lunare si semestriale.'),
);
?>
<h1>Bun venit!</h1>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => array(
        array('label' => 'Contul meu', 'url' => array('account/index')),
        array('label' => 'Lista de școli înscrise în SIP', 'url' => array('school/index')),
        array('label' => 'Vacanțe', 'url' => array('breaks/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
        array('label' => 'Centrul de ajutor', 'url' => array('/help/post/index')),
        array('label' => 'Întrebări de securitate', 'url' => array('securityQuestion/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
    ),
    'htmlOptions' => array(
        'class' => 'bigmenu',
    ),
));
$myStuff = array();
$students = Yii::app()->user->model()->rStudent;
if (!empty($students)) {
    foreach ($students as $student) {
        $myStuff[] = array('label' => $student->name, 'url' => array('student/view', 'id' => $student->id));
    }
}
$classes = Yii::app()->user->model()->rClass;
if (!empty($classes)) {
    foreach ($classes as $class) {
        $myStuff[] = array('label' => $class->grade.$class->name.' '.$class->profile, 'url' => array('classes/view', 'id' => $class->id));
    }
}
if (!empty($myStuff)) {
    ?><h1>chestii</h1><?php
    $this->widget('zii.widgets.CMenu', array(
    'items' => $myStuff,
    'htmlOptions' => array(
        'class' => 'bigmenu',
    ),
));
}
    
    