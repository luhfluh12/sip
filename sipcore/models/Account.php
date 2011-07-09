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
        return array(
            array('phone', 'required', 'on' => 'insert, update'),
            array('password', 'required', 'on' => 'update'),
            array('old_password', 'required', 'on' => 'update'),
            array('old_password', 'validatePassword_rule', 'on' => 'update'),
            array('new_password', 'compare', 'compareAttribute' => 'new_password2', 'on' => 'update, setupPassword'),
            array('phone', 'numerical', 'allowEmpty' => false, 'integerOnly' => true),
            array('phone', 'unique', 'allowEmpty' => false),
            array('email', 'email', 'allowEmpty' => true),
            array('email', 'unique', 'allowEmpty' => true),
            array('name', 'filter', 'filter' => array('CHtml', 'encode')),
            array('new_password', 'length', 'min' => 6, 'allowEmpty' => true, 'on' => 'update, setupPassword'),
            array('new_password, new_password2', 'safe', 'on' => 'setupPassword'),
            array('phone, password, old_password, email, name', 'unsafe', 'on' => 'setupPassword'),
            array('old_password, new_password, new_password2', 'safe', 'on' => 'update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('email', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
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
            'name' => 'Nume complet (nume, prenume)',
            'email' => 'Adresă e-mail',
            'password' => 'Parolă',
            'phone' => 'Telefon',
            'old_password' => 'Parola',
            'new_password' => 'Parolă nouă',
            'new_password2' => 'Parolă nouă (verificare)',
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
        } while (Account::model()->findByAttributes(array('activation' => $this->activation)) !== null);
    }

    /**
     * Get Account model by the specified activation code
     * @param string $code The activation code user inserted
     * @return Account The requested model or NULL if it doesn't exist
     */
    public static function findByActivationCode($code) {
        $code = hash('sha512', $code);
        return Account::model()->findByAttributes(array('activation' => $code));
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

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->generateActivationCode();
            } else {
                if (!empty($this->new_password)) {
                    $this->password = $this->hashPassword($this->new_password);
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
