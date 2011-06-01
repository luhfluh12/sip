<?php
$this->breadcrumbs=array(
    'Școli'=>array('school/index'),
    $classes->rSchool->name=>array('school/view','id'=>$classes->rSchool->id),
    $classes->grade.' '.$classes->name=>array('classes/view','id'=>$classes->id),
    '',
);

$this->menu=array(
	array('label'=>'Listă elevi', 'url'=>array('index'),'visible'=>Yii::app()->user->checkAccess('admin')),
	array('label'=>'Administrare elevi', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
        array('label'=>'Clasa '.$classes->grade.' '.$classes->name, 'url'=>array('classes/view','id'=>$classes->id)),
);

$this->pageTitle = 'Adaugă elev';

if (isset($_GET['saved']) && $_GET['saved']==1): ?>
    <div class="flash-success">Elevul a fost adăugat cu succes.</div>
<?php endif; ?>
<?php if (isset($_GET['saved']) && $_GET['saved']==0): ?>
    <div class="flash-error">Elevul nu a putut fi adăugat în baza de date. Verificați numărul de elevi alocat acestei clase.</div>
<?php endif; ?>    

<h1>Adaugă un elev în clasa <?php echo $classes->grade.' '.$classes->name; ?></h1>

<?php echo $this->renderPartial('_form', array(
                    'student'=>$student,
                    'parent'=>$parent,
                    //'sAccount'=>$sAccount,
                    'pAccount'=>$pAccount,
		)); ?>