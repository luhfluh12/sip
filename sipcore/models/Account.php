<?php

/**
 * This is the model class for table "accounts".
 *
 * The followings are the available columns in table 'accounts':
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $salt
 * @property string $activation
 * @property integer $last_login
 * @property string $last_ip
 * @property integer $registered
 * @property integer $sms_hour1
 * @property integer $sms_hour2
 * @property string $security_question
 * @property string $security_answer
 * @property array $rAccountRevisions
 */
class Account extends CActiveRecord {
    const TYPE_TEACHER=2;
    const TYPE_PARENT=4;
    const TYPE_ADMIN=5;
    const TYPE_WORKER=6;
    private $_activation = null;
    public $old_password;
    public $new_password;
    public $new_password2;

    /**
     * Returns the static model of the specified AR class.
     * @param #D__CLASS__|? $className
     * @return Account the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function getHomePage() {
        if ($this->type == Account::TYPE_PARENT) {
            if ($this->rParent->rStudentCount > 1)
                return array('parents/view', 'id' => $this->info);
            else {
                $g = $this->rParent->rStudent;
                if (is_array($g) && $g[0] !== null)
                    return array('student/view', 'id' => $g[0]->id);
                else
                    return array('parents/view', 'id' => $this->info);
            }
        } elseif ($this->type == Account::TYPE_TEACHER)
            return array('classes/view', 'id' => $this->rTeacher->class);
        else
            return array('account/index');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'accounts';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        // scenarios: chgeneral, chpassword, chemail, chphone, chquestion, setupPassword, insert
        return array(
            array('phone', 'required', 'on' => 'insert, chphone'),
            array('phone', 'match', 'pattern' => '/^\+?[0-9]{7,15}$/', 'on' => 'insert, chphone'),
            array('phone', 'unique', 'allowEmpty' => false, 'on' => 'insert, chphone'),
            array('new_password', 'length', 'min' => 6, 'allowEmpty' => true, 'on' => 'setupPassword, chpassword'),
            array('new_password, new_password2', 'safe', 'on' => 'setupPassword, chpassword'),
            array('new_password', 'compare', 'compareAttribute' => 'new_password2', 'on' => 'chpassword, setupPassword'),
            array('old_password', 'required', 'on' => 'chpassword', 'message' => 'Trebuie să scrieți parola veche.'),
            array('old_password', 'validatePassword_rule', 'on' => 'chpassword', 'skipOnError' => true),
            array('email', 'email', 'allowEmpty' => true, 'on' => 'insert, chemail'),
            array('email', 'unique', 'allowEmpty' => true, 'on' => 'insert, chemail'),
            array('name', 'filter', 'filter' => array('CHtml', 'encode'), 'on' => 'chgeneral'),
            array('sms_hour1, sms_hour2', 'numerical', 'min' => 0, 'max' => 23, 'integerOnly' => true, 'allowEmpty' => false, 'on' => 'chgeneral'),
            array('security_question', 'numerical', 'integerOnly' => true, 'allowEmpty' => false, 'on' => 'chquestion'),
            array('security_question', 'exist', 'className' => 'SecurityQuestion', 'attributeName' => 'id', 'allowEmpty' => false, 'on' => 'chquestion'),
            array('security_answer', 'length', 'min' => 3, 'allowEmpty' => false, 'on' => 'chquestion'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('email, phone, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rAccountRevisions' => array(self::HAS_MANY, 'AccountRevision', 'account'),
            'rSecurityQuestion' => array(self::BELONGS_TO, 'SecurityQuestion', 'security_question'),
            'rStudent' => array(self::HAS_MANY, 'Students', 'parent'),
            'rStudentCount' => array(self::STAT, 'Students', 'parent'),
            'rClass' => array(self::HAS_ONE, 'Classes', 'teacher'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'type' => 'Tip cont',
            'name' => 'Nume complet',
            'email' => 'Adresă e-mail',
            'password' => 'Parolă',
            'phone' => 'Telefon',
            'old_password' => 'Parola',
            'new_password' => 'Parolă nouă',
            'new_password2' => 'Parolă nouă (verificare)',
            'security_question' => 'Întrebare de securitate',
            'security_answer' => 'Răspunsul întrebării de securitate',
            'registred' => 'Înregistrat',
            'sms_hour1' => 'Primesc SMS-uri începând cu ora',
            'sms_hour2' => 'Primesc SMS-uri până la ora',
        );
    }

    /**
     * Generates a salt based on Account ID and password length and
     * hashes the password using it.
     * @param string $password
     * @return string SHA-512 hashed $password 
     */
    private function hashPassword($password) {
        // getting salt:
        mt_srand(strlen($password) * $this->id);
        $saltLength = mt_rand(10, 20);
        $salt = $this->randomString($saltLength);
        // salting the password:
        $cut = intval(mb_strlen($password) / 3);
        $password = mb_substr($password, 0, $cut) . $salt . mb_substr($password, $cut + 1);
        // hashing:
        return hash("sha512", $password);
    }

    /**
     * Check if the given password matches the password of this account
     * @param string $password
     * @return boolean Whether the password matches 
     */
    public function validatePassword($password) {
        return $this->password === $this->hashPassword($password);
    }

    /**
     * Checks the answer of the security question
     * @param string $answer
     * @return boolean Whether the answer is correct or not
     */
    public function validateQuestion($answer) {
        return $this->security_answer === $this->hashPassword(strtolower($answer));
    }

    /**
     * Adds an error if the given attribute is not the password of the account
     * Checking is done using @link $this->validatePassword
     * @param string $attribute
     */
    public function validatePassword_rule($attribute) {
        if ($this->validatePassword($this->{$attribute}) === false)
            $this->addError($attribute, 'Parola este incorectă.');
    }

    /**
     * Generates a random simple code for account activation.
     * Updates $this->_activation with the code and $this->activation with the storable hash
     */
    public function generateActivationCode($len=10) {
        // generationg the code
        do {
            $this->_activation = $this->randomString($len, true);
            $this->activation = hash("sha512", $this->_activation);
        } while (Account::model()->exists('activation=:a', array(':a' => $this->activation)) === true);
    }

    /**
     * Get Account model by the specified activation code
     * @param string $code The activation code user inserted
     * @param string $condition findByAttributes second argument
     * @param array $params findByAttributes third argument
     * @return Account The requested model or NULL if it doesn't exist
     */
    public static function findByActivationCode($code, $condition='', $params=array()) {
        $code = hash('sha512', $code);
        return Account::model()->findByAttributes(array('activation' => $code), $condition, $params);
    }

    /**
     * Get Account model by the login value (email or phone)
     * @param string $login Email or phone to search for
     * @param string $condition findByAttributes second argument
     * @param array $params findByAttributes third argument
     * @return Account The requested model or NULL if it doesn't exist
     */
    public static function findByLogin($login, $condition='', $params=array()) {
        if (mb_strpos($login, '@'))
            return self::model()->findByAttributes(array('email' => $login), $condition, $params);
        return self::model()->findByAttributes(array('phone' => $login), $condition, $params);
    }

    /**
     * Checks if the user account is activated successfully.
     * @return boolean Whether the user can login or not. 
     */
    public function canLogIn() {
        return $this->activation == false && $this->password == true;
    }

    public function sendActivationCode() {
        if ($this->email) {
            // sending activation e-mail
            $message = new YiiMailMessage;
            $message->view = 'register';
            $message->setBody(array('model' => $this, 'activation' => $this->_activation), 'text/html');
            $message->subject = 'Înregistrare SIP';
            $message->addTo($this->email);
            $message->from = Yii::app()->params['infoEmail'];
            Yii::app()->mail->send($message);
        } else {
            // sending activation sms
            $message = new Sms;
            $message->added = time();
            $message->student = $this->info;
            $message->to = $this->phone;
            $message->message = 'Vizitați http://siponline.ro/ pentru a vă activa contul folosind codul: ' . $this->_activation;
            $message->status = Sms::STATUS_TOSEND;
            $message->save();
        }
    }

    /**
     * Stores a new AccountRevision, using the Account's scenario
     * and the given old value
     * @param string $oldvalue The value that was changed
     * @param bool|string $action The specified action. Defaults to the current scenario
     * @return boolean Whether the revision was saved
     */

    public function storeOldValue($oldvalue, $action=false) {
        $revision = new AccountRevision;
        $revision->oldvalue = $oldvalue;
        $revision->action = $action === false ? $this->getScenario() : $action;
        $revision->account = $this->id;
        return $revision->save();
    }

    /**
     * Changes the given romanian phone number in international format
     * If the no starts with a +, the value same value will be returned
     * @param string $no The number to be standardized (internationalized)
     * @return string The number in international format
     */
    public static function standardizePhone($no) {
        if (mb_strlen($no) === 10 && mb_strcut($no, 0, 2) === '07') {
            return '+4' . $no;
        } elseif (mb_strlen($no) === 9 && mb_strcut($no, 0, 1) === '7') {
            return '+40' . $no;
        }
        return $no;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->phone = self::standardizePhone($this->phone);

            // salt and hash the security answer
            if ($this->getScenario() === 'chquestion') {
                $this->security_answer = $this->hashPassword(strtolower($this->security_answer));
            }
            if ($this->isNewRecord) {
                $this->generateActivationCode();
            } else {
                if (!empty($this->new_password)) {
                    $this->password = $this->hashPassword($this->new_password);
                    if ($this->storeOldValue('', 'chpassword') === false) {
                        $this->addError('new_password', 'A apărut o eroare. Vă rugăm încercați din nou.');
                        return false;
                    }
                }
            }
            return true;
        } else
            return false;
    }

    protected function afterSave() {
        parent::afterSave();
        if ($this->_activation !== null) {
            $this->sendActivationCode();
        }
    }

    /**
     * Returnează un text pentru constantele de genul Account::TYPE_* sau un array asociativ
     * @param mixed $type FALSE (default) pentru lista completă și integer pentru numele unui tip de cont
     * @return mixed array cu lista (TYPE_*=>"denumire") sau string cu denumirea
     */
    public static function getAccountTypes($type=false) {
        $types = array(
            self::TYPE_TEACHER => 'Profesor',
            self::TYPE_PARENT => 'Părinte',
            self::TYPE_ADMIN => 'Administrator',
            self::TYPE_WORKER => 'Moderator',
        );
        if ($type === false)
            return $types;
        return $types[$type];
    }

    /**
     * Returns the best display name for the current account.
     * @return string The name of the selected Account
     */
    public function getName() {
        if ($this->name)
            return $this->name;
        elseif ($this->email)
            return $this->email;
        return $this->phone;
    }

    /**
     * Returns a random generated string using mb_rand
     * @param integer $len The length of the string
     * @param boolean $simple Whether to use only uppercase alphanumeric characters
     * @return string The generated string
     */
    public function randomString($len, $simple=false) {
        if ($simple === true) {
            $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        } else {
            $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789`~!@#$%^&*()_+-='\";:[]{}\\|/?.,<>";
        }
        $max = strlen($letters) - 1;
        $result = '';
        for ($i = 0; $i < $len; $i++) {
            $result .= $letters[mt_rand(0, $max)];
        }
        return $result;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('type', $this->type);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }
}
