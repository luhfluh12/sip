<?php

/**
 * This is the model class for table "marks".
 *
 * The followings are the available columns in table 'marks':
 * @property integer $id
 * @property integer $student
 * @property integer $subject
 * @property integer $mark
 * @property string $date
 * @property integer $type
 */
class Mark extends Schoolitem
{
        const TYPE_NORMAL = 1; //notă normală
        const TYPE_THESIS = 2; //teză
	/**
	 * Returns the static model of the specified AR class.
	 * @return Mark the static model class
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
		return 'marks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student, subject, mark, date', 'required'),
			array('student, subject', 'numerical', 'integerOnly'=>true),
                        array('mark','numerical','integerOnly'=>true,'max'=>10,'min'=>1),
                        array('subject','exist','className'=>'Subject','attributeName'=>'id','allowEmpty'=>false),
                        array('student','exist','className'=>'Student','attributeName'=>'id','allowEmpty'=>false),
                        // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student, subject, mark, date', 'safe', 'on'=>'search'),
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


        
        public function isUniqueMark() {
            $return = $this->exists('date=:da AND subject=:su AND student=:st', array(
                ':da'=>$this->date,
                ':su'=>$this->subject,
                ':st'=>$this->student,
            )) === false;
            if (!$return) $this->addError ('date', 'Elevul are deja o notă în această zi.');
            return $return;
        }

        public function deleteCurrentThesis($student, $subject) {
            $t = time();
            $schoolyear=Schoolyear::model()->findByDate($t);
            $semester=Schoolyear::model()->getSemesterByDate($t, $schoolyear->change);
            return $this->deleteAll('type=:type AND student=:student AND subject=:subject AND schoolyear=:schoolyear AND semester=:semester',
                    array(
                        ':type'=>self::TYPE_THESIS,
                        ':student'=>$student,
                        ':subject'=>$subject,
                        ':schoolyear'=>$schoolyear->id,
                        ':semester'=>$semester,
                    ));
        }
        public function getCurrentThesis ($student,$subject) {
            $t = time();
            $schoolyear=Schoolyear::model()->findByDate($t);
            $semester=Schoolyear::model()->getSemesterByDate($t, $schoolyear->change);
            return $this->find('type=:type AND student=:student AND subject=:subject AND schoolyear=:schoolyear AND semester=:semester',
                    array(
                        ':type'=>self::TYPE_THESIS,
                        ':student'=>$student,
                        ':subject'=>$subject,
                        ':schoolyear'=>$schoolyear->id,
                        ':semester'=>$semester,
                    ));
        }
        
        protected function beforeSave () {
            if (parent::beforeSave()) {
                if ($this->rSubject->show==0)
                        return false;

                $this->added=time();
                if ($this->getScenario()==='thesis') {
                    $this->date=$this->added;
                    $this->type=self::TYPE_THESIS;
                } else {
                    $this->date=strtotime($this->date.'.'.Schoolyear::yearByMonth($this->date));
                    $this->type=self::TYPE_NORMAL;
                }

                
                if ($this->getScenario()!=='thesis' && !$this->isUniqueMark())
                    return false;

                if (!$this->validateSchoolyear($this->getScenario() === 'thesis' ? false : true))
                    return false;
                
                if ($this->getScenario()!=='thesis') {
                    if (!Schedule::hasStudentSubject($this->subject, $this->student, $this->date)){
                        $this->addError('date', 'Această materie nu este în orar '.strtolower(Schedule::getWeekday(date('w',$this->date))));
                        return false;
                    }
                    $break = Breaks::model()->checkDate($this->date);
                    if ($break!==true) {
                        $this->addError('date', 'Nu poți adăuga note în '.$break);
                        return false;
                    }
                }
                return true;
            } else
                return false;
        }
        
        protected function afterSave () {
            parent::afterSave();
            $average = new Averages;
            $average->student=$this->student;
            $average->subject=$this->subject;
            $average->added=$this->added;
            $average->schoolyear=$this->schoolyear;
            $average->semester=$this->semester;
            $average->date=$this->date;
            $average->type=($this->type===self::TYPE_NORMAL ? Averages::TYPE_CHART : Averages::TYPE_CHART_THESIS);
            $s = $average->save();
            if (!$s) return false;
            Averages::model()->updateAverages($this->student, $this->subject, $this->schoolyear, $this->semester, $this->date ? $this->date : $this->added);
           return true;
            
        }
        
        protected function afterDelete() {
            parent::afterDelete();
            Averages::model()->deleteAllByAttributes(array(
                'student'=>$this->student,
                'subject'=>$this->subject,
                'date'=>($this->date ?  $this->date : $this->added),
            ));
            Averages::model()->updateAverages($this->student, $this->subject, $this->schoolyear, $this->semester, $this->date ? $this->date : $this->added);
            return true;
        }
        
        public function getAverageStudentSubject($student,$subject,$schoolyear,$semester,$timelimit=false) {
            if ($timelimit===false) $timelimit = time();
            $query ="SELECT AVG(mark) AS avg FROM ".$this->tableName()."
                WHERE student=:student
                AND subject=:subject
                AND schoolyear=:schoolyear
                AND semester=:semester
                AND date<=:t
                AND type=:type";
            $command =Yii::app()->db->createCommand($query);
            $command->bindValues(array(
                ':student'=>$student,
                ':subject'=>$subject,
                ':schoolyear'=>$schoolyear,
                ':semester'=>$semester,
                ':t'=>$timelimit,
                ':type'=>self::TYPE_NORMAL,
            ));
            $data = $command->queryColumn();
            $avg_marks = $data[0];
            
            // check if thesis
            $query = "SELECT mark AS thesis FROM ".$this->tableName()."
                WHERE student=:student
                AND subject=:subject
                AND schoolyear=:schoolyear
                AND semester=:semester
                AND added<=:t
                AND type=:type";
            $command=Yii::app()->db->createCommand($query);
            $command->bindValues(array(
                ':student'=>$student,
                ':subject'=>$subject,
                ':schoolyear'=>$schoolyear,
                ':semester'=>$semester,
                ':t'=>$timelimit,
                ':type'=>self::TYPE_THESIS,
            ));
            $data = $command->queryColumn();
            if (isset($data[0]) && $data[0]!=0)
                $avg_final = (float) ((3*$avg_marks)+$data[0])/4;
            else
                $avg_final = $avg_marks;
            return $avg_final;
        }
        
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                    'id' => 'ID',
                    'student' => 'Elev',
                    'subject' => 'Materie',
                    'mark' => 'Notă',
                    'date' => 'Data din catalog:',
                    'added' => 'Data ultimei modificări în SIP:',
                    'schoolyear' => 'Anul școlar',
                    'semester' => 'Semestrul',
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
		$criteria->compare('student',$this->student);
		$criteria->compare('subject',$this->subject);
		$criteria->compare('mark',$this->mark);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

}