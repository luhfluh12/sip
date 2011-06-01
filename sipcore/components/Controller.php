<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        /**
         * @var array If sip_title is an array, it should be specified as arrays with
         * img, text and title keys. The result is some panels. Used for home page.
         * If sip_title is a string, it is the title rendered right after the breadcrumbs.
         * 
         * note: If no sip_title is empty(), the pageTitle will be used instead
         * note2: If sip_title is !empty() array, breadcrumbs are not shown
         */
        public $sip_title=array();
        /**
         * @var array sip_tabs Define them as an array (title=>url).
         * url is an array that will be parsed with createUrl()
         */
        public $sip_tabs=array();
}