<?php

/**
 * This is the model class for table "absences".
 *
 * The followings are the available columns in table 'absences':
 * @property integer $id
 * @property string $date
 * @property integer $subject
 * @property integer $schoolyear
 * @property integer $semester
 * @property string $added
 * @property integer $authorized
 * 
 * @property-read STATUS_UNAUTH unauthorized absence
 * @property-read STATUS_AUTH authorized absence
 */
class Absences extends Schoolitem
{
        const STATUS_AUTH=1;
        const STATUS_UNAUTH=2;
        private $_checkSchedule=true;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Absences the static model class
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
		return 'absences';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, subject, student', 'required'),
			array('subject', 'numerical', 'integerOnly'=>true),
                        array('authorized', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
                        array('subject','exist','className'=>'Subject','attributeName'=>'id','allowEmpty'=>false),
                        array('student','exist','className'=>'Student','attributeName'=>'id','allowEmpty'=>false),
                        array('student, subject, date','unsafe','on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('date, subject', 'safe', 'on'=>'search'),
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
                    'rStudent'=>array(self::BELONGS_TO,'Student','student'),
                    'rSubject'=>array(self::BELONGS_TO,'Subject','subject'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Dată',
			'subject' => 'Materie',
			'schoolyear' => 'An școlar',
			'semester' => 'Semestru',
			'added' => 'Adăugat în SIP',
                        'authorized' => 'Motivată',
		);
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

		$criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('subject',$this->subject);
		$criteria->compare('schoolyear',$this->schoolyear);
		$criteria->compare('semester',$this->semester);
		$criteria->compare('added',$this->added,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
        /**
         *
         * @param start $start used in strtotime()
         * @param name $end used in strtotime(). If empty(), just one day is added
         * @param CActiveRecord $student Student model
         * @return integer no of added absences
         */
        public static function saveInterval ($start, $end, $student) {
            $start = strtotime($start); $added = time();
            $end=empty($end) ? $start : strtotime($end);
            $schedule = Schedule::getClassSchedule($student->class,false);
            $abs=0; $day = 60*60*24;
            $returnArray = array();
            $query = "INSERT INTO ".self::model()->tableName()."
                (date, subject, student, added, authorized, schoolyear, semester) VALUES
                (:date, :subject, :student, :added, :authorized, :schoolyear, :semester)";
            $command=Yii::app()->db->createCommand($query);
            $schoolyear=false;
            while ($start <= $end) {
                $weekday = date('w',$start);
                $_year = Schoolyear::yearByMonth(date('n',$start), date('Y',$start));
                if ($schoolyear!==$_year) {
                    $schoolyear=$_year;
                    $semester = Schoolyear::model()->getSemesterByDate($start);
                }
                if (isset($schedule[$weekday]) && is_array($schedule[$weekday])) {
                    foreach ($schedule[$weekday] as $subject) {
                        $command->execute(array(
                            ':date'=>$start,
                            ':subject'=>$subject,
                            ':schoolyear'=>$schoolyear,
                            ':semester'=>$semester,
                            ':authorized'=>self::STATUS_UNAUTH,
                            ':added'=>$added,
                            ':student'=>$student->id,
                        ));
                        if (isset($returnArray[$subject]) && is_array($returnArray[$subject]))
                            $returnArray[$subject]=array_merge($returnArray[$subject], array($abs=>date('d F Y',$start)));
                        else
                            $returnArray[$subject]=array($abs=>date('d F Y',$start));
                        $abs++;
                    }
                }
                // next day
                $start += $day;
            }
            if ($abs===0) return false;
            $returnArray['added']=$abs;
            return $returnArray;
        }

        protected function beforeSave () {
            if (parent::beforeSave()) {
                if ($this->rSubject->show==0)
                        return false;
                
                if ($this->isNewRecord) {
                    if ($this->_checkSchedule===true) {
                        $this->date=strtotime($this->date.'.'.Schoolyear::yearByMonth($this->date));
                    }
                    if (!$this->validateSchoolyear($this->_checkSchedule))
                        return false;

                    if ($this->_checkSchedule===true && !Schedule::hasStudentSubject($this->subject, $this->student, $this->date)){
                        $this->addError('date', 'Această materie nu este în orar '.strtolower(Schedule::getWeekday(date('w',$this->date))));
                        return false;
                    }
                    $break = Breaks::model()->checkDate($this->date);
                    if ($break!==true) {
                        $this->addError('date', 'Nu poți pune absențe în '.$break);
                        return false;
                    }
                }
                
                if ($this->authorized!=Absences::STATUS_AUTH)
                    $this->authorized = Absences::STATUS_UNAUTH;
                
                $this->added=time();
                return true;
            } else
                return false;
        }
        
        protected function afterSave() {
            parent::afterSave();
            $unauthorized = $this->countByAttributes(array('authorized'=>self::STATUS_UNAUTH));
            if ($unauthorized>Sms::MAX_ABSENCES_ALLOWED) {
               Sms::model()->saveDraft($this->student, Sms::ADD_ABSENCES_WARNING);
            }
            return true;
        }
        
        /**
         * Sets _checkSchedule value. Defaults to true
         * If _checkSchedule is false, beforeSave does not check if the subject
         * is in the schedule at $this->date;
         * @param type $check 
         */
        public function setCheckSchedule($check) {
            $this->_checkSchedule=(bool) $check;
        }

}