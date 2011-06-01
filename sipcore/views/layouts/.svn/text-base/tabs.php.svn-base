<?php 
if (is_array($this->sip_tabs) && !empty($this->sip_tabs)) {
    $this->beginContent('//layouts/main');

    $this->widget('zii.widgets.jui.CJuiTabs', array(
            'tabs' => $this->sip_tabs,
            /*array(
                    'StaticTab 1' => 'Content for tab 1',
                    'StaticTab 2' => array('content' => 'Content for tab 2', 'id' => 'tab2'),
                    // panel 3 contains the content rendered by a partial view
                    'AjaxTab' => array('ajax' => $this->createUrl('/AjaxModule/ajax/reqTest01')),
            ),*/
            // additional javascript options for the tabs plugin
            'options' => array(
                'collapsible' => false,
                'cache'=>true,
                'spinner'=>'se incarca',
            ),
    ));
    $this->endContent();
} else
   throw new CHttpException(404,'No tabs defined. sorry');
?>