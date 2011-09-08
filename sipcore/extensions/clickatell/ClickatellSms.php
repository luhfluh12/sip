<?php

/**
 * Simple Application Component that allows you to send SMSes using Clickatell Gateway easily.
 * Yii::app()->sms()->send(array('to'=>'+40746xxxxxx','message'=>'hello world!');
 * 
 * @link http://google.com
 * @author Vlad Velici
 * @version 0.1
 * @uses CURL
 * @uses Yii::app()->cache
 * @uses Yii::log()
 * When a clickatell request fails, errors are loggeg with 'warning' trace and 'ext.clickatell' category
 * This class does not validate any SMS parameters, not even the phone no.
 * Yii::app()->cache is used to cache Clickatell Session ID
 */
class ClickatellSms extends CApplicationComponent {
    // sms-level settings

    /** @var mixed Mobile phone no to send the SMS to. It can be an array with up to 50 numbers. */
    public $to;

    /** @var string The message to be send */
    public $message;

    /**
     * @var cliMsgId (Optional). Assign an unique ID to your SMS.
     * See CliMsgId in Clickatell docs for more. It helps with callbacks.
     */
    public $smsId = false;

    // api level settings

    /** @var string Your Clickatell username */
    public $clickatell_username;

    /** @var string Your Clickatell password */
    public $clickatell_password;

    /** @var string Your Clickatell API ID */
    public $clickatell_apikey;

    /** @var boolean Whether to use https */
    public $https = true;

    /** @var integer The callback level. More in the Clickatell HTTP API Manual */
    public $callbackLevel = 2;
    // component level
    protected $_session = null;
    const CACHE_ID = 'clickatell-session';
    const CACHE_TIME = 870;

    /**
     * Sends the SMS request to Clickatell gateway. 
     * @param array $config A key=>value array to configure the message
     * @return mixed String with the clickatell SMS ID if it succeeds or FALSE if it fails
     */
    public function send($config) {
        // set per-message config
        foreach ($config as $k => $v) {
            $this->$k = $v;
        }
        if (is_array($this->to))
                $this->to = implode (',', $this->to);
        $params = array(
            'to' => urlencode($this->to),
            'message' => urlencode($this->message),
            'callback' => (int) $this->callbackLevel,
            'session_id' => $this->getSession(),
        );
        if ($this->smsId) {
            $params += array('cliMsgId'=>$this->smsId);
        }
        // send the request to clickatell
        $request = $this->clickatellRequest('send', $options);
    }

    protected function getSession() {
        if ($this->_session === null) {
            $cached = Yii::app()->cache->get(self::CACHE_ID);
            if ($cached !== false) {
                $this->_session = $cached;
            } else {
                $request = $this->clickatellRequest('auth', array(
                    'user' => urlencode($this->clickatell_username),
                    'password' => urlencode($this->clickatell_password),
                    'api_id' => urlencode($this->clickatell_apikey),
                        ));
                if ($request !== false) {
                    Yii::app()->cache->set(self::CACHE_ID, $request, self::CACHE_TIME);
                    $this->_session = $request;
                } else {
                    return false;
                }
            }
        }
        return $this->_session;
    }

    protected function getUrl() {
        if ($this->https === true)
            return 'https://api.clickatell.com/http/';
        return 'http://api.clickatell.com/http/';
    }

    /**
     * Sends an request to Clickatell HTTP API. Error messages are logged.
     * @param string $method The method used. Common mehtods are send, ping and auth. For more check the Clickatell docs.
     * @param array $params Key=>Value array for POST data. Values must be URL-encoded.
     * @return mixed The returned information (without "OK: " status) or FALSE if it fails. 
     */
    protected function clickatellRequest($method, $params) {
        $request = curl_init();
        $postData = '';
        foreach ($params as $name => $value) {
            $postData .= '&' . $name . '=' . $value;
        }
        $postData = substr($postData, 1);
        curl_setopt($request, CURLOPT_POST, $postData);
        curl_setopt($request, CURLOPT_POSTFIELDS, count($params));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        if ($this->https === true) {
            curl_setopt($request, CURLOPT_URL, 'https://api.clickatell.com/http/' . $method);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($request, CURLOPT_CAINFO, Yii::app()->extensionPath . 'clickatell/certificate/api.clickatell.com');
        } else {
            curl_setopt($request, CURLOPT_URL, 'http://api.clickatell.com/http/' . $method);
        }
        $response = curl_exec($request);

        // if the request fails
        if ($response === false) {
            Yii::log('Clickatell SMS Request failed (' . $url . ')', 'warning', 'ext.clickatell');
            return false;
        }

        // all the responses have ": " as a delimiter from status and other informations
        list($status, $info) = explode(': ', $response);

        // if the request was successfully done, return the response text without the status
        if ($status === 'OK') {
            // if there is a session, reset the cache time for it
            if ($this->_session !== null)
                Yii::app()->cache->set(self::CACHE_ID, $this->_session, self::CACHE_TIME);
            return $info;
        }

        // some errors. log the errors and return false
        Yii::log("Clickatell SMS Request failed (" . $url . "): " . $response, 'warning', 'ext.clickatell');
        return false;
    }
}