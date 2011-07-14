<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $_id;

    public function authenticate() {
        $account = Account::model()->findByLogin($this->username);
        if ($account === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$account->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $account->id;
            $this->username = $account->getName();
            $this->errorCode = self::ERROR_NONE;
            $account->last_login = time();
            $account->last_ip = Yii::app()->request->getUserHostAddress();
            $account->update(array('last_login', 'last_ip'));
        }
        return!$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

}