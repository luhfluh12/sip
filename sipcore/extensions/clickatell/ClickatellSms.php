<?php

/**
 * Simple Application Component that allows you to send SMSes using Clickatell Gateway easily.
 * Yii::app()->sms()->send(array('to'=>'+40746xxxxxx','message'=>'hello world!');
 * 
 * @link https://github.com/vladvelici/ClickatellSms
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

    /** @var mixed Mobile phone no to send the SMS to. It can be an array with more numbers. */
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

    /** @var string The type of the SMS (e.g. SMS_TEXT, SMS_FLASH). Check Clickatell docs for more */
    public $smsType = false;

    // component level

    /** @var boolean Whether to print debug information on screen. Useful when debugging from shell */
    public $debug = false;

    /** @var boolean Whether to physically send the messages */
    public $real = true;
    
    protected $_session = null;
    protected $_error = null;
    const CACHE_ID = 'clickatell-session';
    const CACHE_TIME = 870;

    /**
     * Sends the SMS request to Clickatell gateway. 
     * @param array $config A key=>value array to configure the message
     * @return mixed String with the clickatell SMS ID if it succeeds or FALSE if it fails
     */
    public function send($config) {
        // if the real sending is disabled, just return true
        if ($this->real!==true)
            return true;
        
        // if there is no session, return the error
        if (!$this->getSession()) {
            return array('001', 'Authentication failed.');
        }

        // set per-message config
        foreach ($config as $k => $v) {
            $this->$k = $v;
        }

        if (is_array($this->to))
            $this->to = implode(',', $this->to);
        $params = array(
            'to' => urlencode($this->to),
            'text' => urlencode($this->message),
            'callback' => (int) $this->callbackLevel,
            'session_id' => $this->getSession(),
        );
        if ($this->smsId) {
            $params += array('cliMsgId' => $this->smsId);
        }
        if ($this->smsType)
            $params += array('msg_type' => $this->smsType);
        // send the request to clickatell
        $response = $this->clickatellRequest('sendmsg', $params);
        if ($response !== false)
            return $response;
        return $this->_error;
    }

    protected function getSession() {
        if ($this->_session === null) {
            $cached = Yii::app()->cache->get(self::CACHE_ID);
            if ($cached !== false) {
                $this->_session = $cached;
            } else {

                if ($this->debug === true)
                    echo " * No Clickatell API Session cached. Authenticating...\n";

                $request = $this->clickatellRequest('auth', array(
                    'user' => urlencode($this->clickatell_username),
                    'password' => $this->clickatell_password,
                    'api_id' => urlencode($this->clickatell_apikey),
                        ));
                if ($request !== false) {
                    Yii::app()->cache->set(self::CACHE_ID, $request, self::CACHE_TIME);
                    $this->_session = $request;

                    if ($this->debug === true)
                        echo " * Authentication success.\n";
                } else {

                    if ($this->debug === true)
                        echo " * Authentication failed.\n";

                    return false;
                }
            }
        }
        return $this->_session;
    }

    /**
     * Sends an request to Clickatell HTTP API. Error messages are logged.
     * @param string $method The method used. Common mehtods are send, ping and auth. For more check the Clickatell docs.
     * @param array $params Key=>Value array for POST data. Values must be URL-encoded.
     * @return mixed The returned information (without "OK: " status) or FALSE if it fails. 
     */
    protected function clickatellRequest($method, $params) {
        if ($this->debug === true) {
            echo " ** Initializing ", $method, " (", ($this->https ? 'https' : 'http'), ") request...\n";
        }

        $request = curl_init();
        $postData = '';
        foreach ($params as $name => $value) {
            $postData .= '&' . $name . '=' . $value;
        }
        $postData = substr($postData, 1);
        curl_setopt($request, CURLOPT_POST, count($params));
        curl_setopt($request, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        if ($this->https === true) {
            curl_setopt($request, CURLOPT_URL, 'https://api.clickatell.com/http/' . $method);
            curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($request, CURLOPT_URL, 'http://api.clickatell.com/http/' . $method);
        }
        $response = curl_exec($request);

        if ($this->debug === true) {
            echo " ** The Clickatell response (or FALSE if the request failed):\n";
            var_dump($response);
        }

        // if the request fails
        if ($response === false) {
            Yii::log('Clickatell SMS Request failed (' . ($https ? 'https' : 'http') . ' - ' . $method . ')', 'warning', 'ext.clickatell');
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
        Yii::log('Clickatell SMS Request failed (' . ($https ? 'https' : 'http') . ' - ' . $method . '): ' . $response, 'warning', 'ext.clickatell');
        $this->_error = explode(', ', $response);
        return false;
    }

}
