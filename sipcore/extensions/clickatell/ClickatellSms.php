<?php

/**
 * Simple Application Component that allows you to send SMSes using Clickatell Gateway easily.
 * Yii::app()->sms()->send(array('to'=>'+40746xxxxxx','message'=>'hello world!');
 * 
 * @link http://google.com
 * @author Vlad Velici
 * @version 0.1
 * @uses CURL The CURL extension is used to create POST requests
 * @uses file_get_contents() Used if CURL is not available or GET requests are prefered.
 * @
 * 
 * 
 * This class does not validate any SMS parameters, not even the phone no.
 * 
 */
class ClickatellSms extends CApplicationComponent {

    public $to;
    public $message;
    public $clickatell_username;
    public $clickatell_password;
    public $clickatell_apikey;
    public $smsId = false;
    public $callbackLevel = 0;
    public $secure=false;

    /**
     * Sends the SMS request to Clickatell gateway. 
     * @param array $config A key=>value array to configure the message
     * @return mixed String with the clickatell SMS ID if success or array(error_no=>error_message) if it fails
     */
    public function send($config) {
        // set per-message config
        foreach ($config as $k => $v) {
            $this->$k = $v;
        }

        // prepare message
        $this->message = urlencode($this->message);
        $this->to = urlencode($this->to);
        $this->smsId = urlencode($this->smsId);
        $this->callbackLevel = (int) $this->callbackLevel;

        // send the request to clickatell
        return $this->clickatellSend();
    }

    protected function clickatellSend() {
        if ($this->method==='post') {
            // using CURL
            
        }
    }

}