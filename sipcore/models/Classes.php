<?php

/**
 * This is the model class for table "classes".
 *
 * The followings are the available columns in table 'classes':
 * @property integer $id
 * @property integer $school
 * @property integer $grade
 * @property string $name
 * @property string $profile
 */
class Classes extends CActiveRecord
{
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Classes the static model class
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
		return 'classes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school, grade, name, profile', 'required'),
			array('school, grade, students', 'numerical', 'integerOnly'=>true,'on'=>'insert, update'),
			array('name', 'length', 'max'=>10, 'on'=>'insert, update'),
                        array('payment', 'length', 'max'=>15, 'on'=>'insert, update'),
			array('profile', 'length', 'max'=>150, 'on'=>'insert, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, school, grade, name, profile', 'safe', 'on'=>'search'),
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
                    'rSchool'=>array(self::BELONGS_TO,'School','school'),
                    'rStudent'=>array(self::HAS_MANY,'Student','class','order'=>'rStudent.name ASC'),
                    'rStudentCount'=>array(self::STAT, 'Student', 'class'),
                    'rTeacher'=>array(self::HAS_ONE,'Teacher','class'),
                    'rSchedule'=>array(self::HAS_MANY,'Schedule','class'),
                    'rSubjects'=>array(self::MANY_MANY,'Subject','schedule(class,subject)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'school' => 'School',
			'grade' => 'Grade',
			'name' => 'Name',
			'profile' => 'Profile',
		);
	}

        public static function canAddStudent($id) {
            $class=self::model()->with('rStudentCount')->find(array(
                'select'=>'students',
                'condition'=>'id=:id',
                'params'=>array(':id'=>$id)));
            if ($class===null) return false;
            if ($class->rStudentCount < $class->students)
                return true;
            else
                return false;
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
		$criteria->compare('school',$this->school);
		$criteria->compare('grade',$this->grade);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('profile',$this->profile,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
        protected function afterDelete(){
            // get teacher details
            $teacher = Teacher::model()->findByAttributes(array('class'=>$this->id));
            // delete Account
            Account::model()->
                    findByAttributes(array('info'=>$teacher->id,'type'=>Account::TYPE_TEACHER))->
                    delete();
            // delete Teacher
            $teacher->delete();
            
            
            /*
             * @todo auto-delete students and parents in this class
             */
            return true;
        }
        protected function afterFind() {
            parent::afterFind();
            $this->payment = date('d.m.Y');
        }
        protected function beforeSave() {
            if (parent::beforeSave()) {
                $this->payment = strtotime($this->payment);
                return true;
            } else
                return false;
        }
}