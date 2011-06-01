<?php

/**
 * This is the model class for table "accounts".
 *
 * The followings are the available columns in table 'accounts':
 * @property integer $id
 * @property integer $type
 * @property integer $info
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $salt
 */
class Account extends CActiveRecord
{
        const TYPE_SCHOOL=1;
        const TYPE_TEACHER=2;
        const TYPE_STUDENT=3;
        const TYPE_PARENT=4;
        const TYPE_ADMIN=5;
        
        private $_temp_password;
        
        public $old_password;
        public $new_password;
        public $new_password2;
        /**
	 * Returns the static model of the specified AR class.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function getHomePage() {
            if ($this->type==Account::TYPE_PARENT) {
                if ($this->rParent->rStudentCount > 1)
                    return array('parents/view','id'=>$this->info);
                else {
                    $g = $this->rParent->rStudent;
                    if (is_array($g) && $g[0]!==null)
                        return array('student/view','id'=>$g[0]->id);
                    else
                        return array('parents/view','id'=>$this->info);
                }
                    
            } elseif ($this->type==Account::TYPE_TEACHER)
                return array('classes/view','id'=>$this->rTeacher->class);
            elseif ($this->type==Account::TYPE_STUDENT)
              return array('student/view','id'=>$this->info);
            elseif ($this->type==Account::TYPE_SCHOOL)
                return array('school/view','id'=>$this->info);
            else
                return array('account/index');
        }
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('password, phone', 'required', 'on'=>'insert, update'),
                        array('old_password','required','on'=>'update'),
                        array('old_password','validatePassword_rule','on'=>'update'),
                        array('new_password','compare','compareAttribute'=>'new_password2','on'=>'update'),
                        array('phone','numerical','allowEmpty'=>false,'integerOnly'=>true),
                        array('phone','unique','allowEmpty'=>false),
			array('email', 'email','allowEmpty'=>true),
                        array('email','unique','allowEmpty'=>true),
                        array('old_password, new_password, new_password2','safe','on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'rParent'=>array(self::BELONGS_TO,'Parents','info'),
                    //'rStudent'=>array(self::BELONGS_TO,'Student','info'),
                    'rTeacher'=>array(self::BELONGS_TO,'Teacher','info'),
                    'rSchool'=>array(self::BELONGS_TO,'School','info'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type' => 'Tip cont',
			'email' => 'Adresă e-mail',
			'password' => 'Parolă',
                        'phone' => 'Telefon',
                        'old_password'=> 'Parola',
                        'new_password'=>'Parolă nouă',
                        'new_password2'=>'Parolă nouă (verificare)',
                );
	}

        private function hashPassword($password) {
            return hash("sha512",$this->salt.$password);
        }
        public function validatePassword($password) {
            $password=$this->hashPassword($password);
            return $this->password === $password;
        }
        public function validatePassword_rule($attribute,$params) {
            if ($this->validatePassword($this->{$attribute}) === false)
                $this->addError($attribute,'Parola este incorectă.');
        }
        protected function beforeSave() {
            if (parent::beforeSave()) {
                if ($this->isNewRecord) {
                    $this->password=strtoupper($this->randomString(8));
                    $this->_temp_password = $this->password;
                    $this->salt=md5(mt_rand(0, 99999).mt_rand(0, 99999));
                    // hashing password
                    $this->password=$this->hashPassword($this->password);
                } else {
                    if (!empty($this->new_password)) {
                        if (strlen($this->new_password) > 6)
                            $this->password=$this->hashPassword($this->new_password);
                        else {
                            $this->addError('new_password', 'Parola nouă este prea scurtă. Folosiți minim 6 caractere.');
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
            if ($this->getScenario()=='insert') {
                if ($this->email) {
                    // sending activation e-mail
                    $message = new YiiMailMessage;
                    $message->view = 'register';
                    $message->setBody(array('model'=>$this),'text/html');
                    $message->subject='Înregistrare SIP';
                    $message->addTo($this->email);
                    $message->from=Yii::app()->params['infoEmail'];
                    Yii::app()->mail->send($message);
                } else {
                    // sending activation sms
                    $message = new Sms;
                    $message->added=time();
                    $message->student=$this->info;
                    $message->to=$this->phone;
                    $message->message='Contul dvs. SIPonline.ro a fost creat. Parola dvs. temporara este: '.$this->_temp_password;
                    $message->status=Sms::STATUS_TOSEND;
                    $message->save();
                }
            }
        }
        
        
        /**
         * @param $type=false integer id-ul tipului de la care vrei sa iei numele
         * @return array cu lista de tipuri de cont posibile de forma ID => denumire sau numele tipului $type
         */
        
        public static function getAccountTypes($type=false) {
            $types = array(
                self::TYPE_SCHOOL => 'Școală',
                self::TYPE_TEACHER => 'Profesor',
                self::TYPE_STUDENT => 'Elev',
                self::TYPE_PARENT => 'Părinte',
                self::TYPE_ADMIN => 'Administrator',
            );
            if ($type===false)
                return $types;
            return $types[$type];
        }
 
        public function getName () {
            if ($this->type==self::TYPE_SCHOOL) {
                return $this->rSchool->name;
            } elseif ($this->type==self::TYPE_TEACHER) {
                return $this->rTeacher->name;
            } elseif ($this->type==self::TYPE_PARENT) {
                return $this->rParent->name;
            } elseif ($this->type==self::TYPE_STUDENT) {
                return $this->rStudent->name;
            }
            return ($this->email ? $this->email : $this->phone);
        }
        
        public function haveAccess($info, $type) {
            if ($this->type != $type)
                    return false;
            if ($this->info != $info)
                    return false;
            return true;
        }
        
        public function randomString($len) {
            $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $max = strlen($letters)-1;
            $result = '';
            for ($i=0;$i<$len;$i++) {
                $result .= $letters[mt_rand(0,$max)];
            }
            return $result;        
        }
        
        /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('type',$this->type);
		$criteria->compare('email',$this->email,true);
                
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	/**
	* getter functino for _temp_password
	* @return mixed The temporary password if it exists or FALSE if it is not set.
	*/
	public function getTempPassword() {
		if (isset($this->_temp_password))
			return $this->_temp_password;
		return false;
	}

}
