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
                <div id="logo">
                    <?php echo CHtml::link('<img src="images/web.gif" alt="siponline" />', array('site/index')); ?>
                    
                </div>
                <?php if (!Yii::app()->user->isGuest): ?>
                    <div id="usermenu">
                        <span><?php echo Yii::app()->user->name; ?></span>
                        <?php echo CHtml::link('Contul meu', array('account/update')); ?> - 
                        <?php echo CHtml::link('Deconectare', array('site/logout')); ?>
                    </div><!-- mainmenu -->
                <?php endif; ?>
            </div><!-- header -->
            <div id="page_title">
                <div id="page_title_content" class="clearfix">
                    <?php
                    // copied from header.php
                    if (is_array($this->sip_title) && !empty($this->sip_title)) {
                        if (!isset($this->sip_title[1]))
                            $this->renderPartial($this->sip_title[0]);
                        else {
                            if (!is_array($this->sip_title[1]))
                                $this->sip_title[1] = array('sip_title' => $this->sip_title[1]);
                            $this->renderPartial($this->sip_title[0], $this->sip_title[1]);
                        }
                    } else {
                        $this->renderPartial('//layouts/headers/normal', array('sip_title' => empty($this->sip_title) ? $this->pageTitle : $this->sip_title));
                    }
                    ?>
                </div>
            </div>
            <div id="page_content">
                <?php echo $content; ?>
            </div>
            <div id="footer">
                <div>
                    <?php echo CHtml::link('Despre', array('/site/page', 'view' => 'about')); ?> -
                    <?php echo CHtml::link('Termeni și condiții de utilizare', array('/site/page', 'view' => 'terms')); ?> -
                    <a href="http://blog.siponline.ro" target="_blank" title="Blogul de dezvoltare a proiectului">Blog de dezvoltare</a> -
                    <?php echo CHtml::link('Contact', array('/site/contact')); ?>
                </div>
                Copyright &copy; 2011 by Vlad Velici.<br/>
                All Rights Reserved.
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>






