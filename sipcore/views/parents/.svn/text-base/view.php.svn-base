<?php
$this->sip_title='Bun venit, '.$model->name;
?>

<h1>Alegeți a cui situație școlară doriți să o vizualizați</h1>

<?php 
foreach ($model->rStudent as $student) {
    $this->renderPartial('_students',array('data'=>$student));
}
?>