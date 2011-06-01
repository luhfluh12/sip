<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div id="page">

	<div id="header">
		<div id="logo"><img src="images/web.gif" alt="siponline" /></div>
        	<div id="mainmenu">
                 <?php 
                 $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                        array('label'=>'Home', 'url'=>array('/site/index'),
                            'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Home', 'url'=>isset(Yii::app()->user->homepage) ? Yii::app()->user->homepage : array('account/index'),
                            'visible'=>!Yii::app()->user->isGuest),
                        array('label'=>'Scoli', 'url'=>array('/school/index'),
                            'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Contul meu',
                            'url'=>array('/account/update','id'=>Yii::app()->user->id),
                            'visible'=>!Yii::app()->user->isGuest),
                        array('label'=>'Logout ('.Yii::app()->user->name.')',
                            'url'=>array('/site/logout'),
                            'visible'=>!Yii::app()->user->isGuest),
                ),
                ));
                ?>
                </div><!-- mainmenu -->
	</div><!-- header -->
        <div id="page_title">
            <div id="page_title_content" class="clearfix">
                <?php 
                    $this->renderPartial('/layouts/header');
                ?>
            </div>
        </div>
        <div id="page_content">
	<?php echo $content; ?>
        </div>
	<div id="footer">
            <div>
                <?php echo CHtml::link('Despre',array('site/page','view'=>'about')); ?> -
                <?php echo CHtml::link('Termeni și condiții de utilizare',array('site/page','view'=>'terms')); ?> -
                <?php echo CHtml::link('Ce spun diriginții?',array('site/page','view'=>'reviews')); ?> -
                <?php echo CHtml::link('Contact',array('site/contact')); ?>
            </div>
		Copyright &copy; <?php echo date('Y'); ?> by Vlad Velici.<br/>
		All Rights Reserved.
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>






