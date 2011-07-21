<?php

/**
 * This is the model class for table "absencesHistory".
 *
 * The followings are the available columns in table 'absencesHistory':
 * @property integer $student
 * @property integer $year
 * @property integer $semester
 * @property integer $auth
 * @property integer $unauth
 *
 * The followings are the available model relations:
 * @property Schoolyear $year0
 * @property Students $student0
 */
class AbsencesHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AbsencesHistory the static model class
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
		return 'absencesHistory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student, year, semester, auth, unauth', 'required'),
			array('student, year, semester, auth, unauth', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('student, year, semester, auth, unauth', 'safe', 'on'=>'search'),
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
			'year0' => array(self::BELONGS_TO, 'Schoolyear', 'year'),
			'student0' => array(self::BELONGS_TO, 'Students', 'student'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'student' => 'Student',
			'year' => 'Year',
			'semester' => 'Semester',
			'auth' => 'Auth',
			'unauth' => 'Unauth',
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

		$criteria->compare('student',$this->student);
		$criteria->compare('year',$this->year);
		$criteria->compare('semester',$this->semester);
		$criteria->compare('auth',$this->auth);
		$criteria->compare('unauth',$this->unauth);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}