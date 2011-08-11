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
class Classes extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Classes the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'classes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school, payment, students', 'unsafe', 'on' => 'updatePart'),
            array('grade, name, profile', 'required'),
            array('school', 'required', 'on' => 'insert, update'),
            array('school, grade, students', 'numerical', 'integerOnly' => true, 'on' => 'insert, update'),
            array('school','exist','className'=>'School','attributeName'=>'id','on'=>'insert, update'),
            array('name', 'length', 'max' => 10, 'on' => 'insert, update, updatePart'),
            array('payment', 'safe', 'on' => 'insert, update'),
            array('profile', 'length', 'max' => 150, 'on' => 'insert, update, updatePart'),
            array('name,profile','filter','filter'=>array('CHtml','encode')),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, school, grade, name, profile', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rSchool' => array(self::BELONGS_TO, 'School', 'school'),
            'rStudents' => array(self::HAS_MANY, 'Student', 'class', 'order' => 'rStudents.name ASC'),
            'rStudentCount' => array(self::STAT, 'Student', 'class'),
            'rAccount' => array(self::BELONGS_TO, 'Account', 'teacher'),
            'rSchedule' => array(self::HAS_MANY, 'Schedule', 'class'),
            'rSubjects' => array(self::MANY_MANY, 'Subject', 'schedule(class,subject)', 'order'=>'rSubjects.name ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'school' => 'Școală',
            'grade' => 'Clasă (9, 10, 11 etc.)',
            'name' => 'Denumire (A,B,C etc.)',
            'profile' => 'Profil',
            'payment' => 'Data următoarei plăți',
            'students' => 'Numărul maxim de elevi permis',
        );
    }

    public static function canAddStudent($id) {
        $class = self::model()->with('rStudentCount')->find(array(
                    'select' => 'students',
                    'condition' => 'id=:id',
                    'params' => array(':id' => $id)));
        if ($class !== null && $class->rStudentCount < $class->students)
            return true;
        return false;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('school', $this->school);
        $criteria->compare('grade', $this->grade);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('profile', $this->profile, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    protected function afterFind() {
        parent::afterFind();
        $this->payment = date('d.m.Y', $this->payment);
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->payment = strtotime($this->payment);
            return true;
        } else
            return false;
    }

}