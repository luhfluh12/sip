<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    /**
     * @var mixed If it is an array, the first argument is the view to be used to render the header 
     * and the second is a string to be used as the actual title. If it is a string, it is the page
     * title. The default view is "//layouts/headers/normal.php"
     */
    public $sip_title = array();

    /**
     * @var array sip_tabs Define them as an array (title=>url).
     * url is an array that will be parsed with createUrl()
     */
    public $sip_tabs = array();
}