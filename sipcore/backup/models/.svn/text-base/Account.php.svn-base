<?php

/**
 * This is the model class for table "accounts".
 *
 * The followings are the available columns in table 'accounts':
 * @property integer $id
 * @property integer $type
 * @property integer $info
 * @property string $email
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
			array('email, password', 'required', 'on'=>'insert, update'),
                        array('old_password','validatePassword_rule','on'=>'update'),
                        array('new_password','compare','compareAttribute'=>'new_password2','on'=>'update'),
			array('email', 'email'),
                        array('email','unique'),
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
                    'rStudent'=>array(self::BELONGS_TO,'Student','info'),
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
            if ($this->isNewRecord) {
                $this->salt=md5(mt_rand(0, 99999));
                // sending activation e-mail
                /*$message = new YiiMailMessage;
                $message->view = 'register';
                $message->setBody(array('model'=>$this),'text/html');
                $message->addTo($this->email);
                $message->from=Yii::app()->params['adminEmail'];
                Yii::app()->mail->send($message);*/
                
                // hashing password
                $this->password=$this->hashPassword($this->password);
            } else {
                if (isset($this->old_password) && !empty($this->old_password) &&
                        $this->validatePassword($this->old_password)) {
                    if ($this->new_password === $this->new_password2 && strlen($this->new_password) > 6)
                        $this->password=$this->hashPassword($this->new_password);
                }
            }
            return true;
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
            else
                return $types[$type];
        }
        
        public function haveAccess($info, $type) {
            if ($this->type !== $type)
                    return false;
            if ($this->info !== $info)
                    return false;
            return true;
        }
        
        public function randomString($len) {
            $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
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
}