<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
        private $_id;
        
	public function authenticate()
	{
                if (strpos($this->username, '@')!==false) {
                    $email=strtolower($this->username);
                    $account= Account::model()->find("LOWER(email)=?",array($email));
                } else {
                    $account=Account::model()->find("phone=?",array($this->username));
                }
		if($account===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$account->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
                        $this->_id=$account->id;
                        $this->username=$account->getName();
                        $this->setState('homepage', $account->getHomePage());
			$this->errorCode=self::ERROR_NONE;
			//$user->user_last_login=time();
			//$user->user_last_ip=Yii::app()->request->getHostAdress();
			//$user->save();
                }
		return !$this->errorCode;
	}
        public function getId(){
            return $this->_id;
        }
}