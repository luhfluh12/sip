<?php

/**
 * This is the model class for table "students".
 *
 * The followings are the available columns in table 'students':
 * @property integer $id
 * @property integer $class
 * @property integer $school
 * @property integer $parent
 * @property string $name
 * @property string $phone
 */
class Student extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Student the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'students';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required', 'on'=>'insert,update'),
            array('name', 'length', 'max' => 200, 'on'=>'insert, update'),
            array('name', 'filter', 'filter' => array('CHtml', 'encode'), 'on'=>'insert, update'),
            array('class', 'exist', 'className'=>'Classes', 'attributeName'=>'id', 'on'=>'insert'),
            array('school', 'exist', 'className'=>'School', 'attributeName'=>'id', 'on'=>'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('class, school, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rClass' => array(self::BELONGS_TO, 'Classes', 'class'),
            'rSchool' => array(self::BELONGS_TO, 'School', 'school'),
            'rParent' => array(self::BELONGS_TO, 'Account', 'parent'),
            'rMarks' => array(self::HAS_MANY, 'Mark', 'student', 'order'=>'date ASC', 'on'=>'date!=0'),
            'rAbsences' => array(self::HAS_MANY, 'Absence', 'student'),
            'rSms' => array(self::HAS_MANY, 'Sms', 'student', 'order' => 'sent, added DESC', 'on' => 'status=' . Sms::STATUS_SENT),
            'rSmses' => array(self::HAS_MANY, 'Sms', 'student', 'order' => 'sent, added DESC'),
            'rChart' => array(self::HAS_MANY, 'Chart', 'student', 'order' => 'subject ASC, date ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'class' => 'Clasă',
            'school' => 'Școală',
            'parent' => 'ID Părinte',
            'name' => 'Nume elev',
        );
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
        $criteria->compare('class', $this->class);
        $criteria->compare('school', $this->school);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('name', $this->name, true);


        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if (Classes::canAddStudent($this->class) === true)
                return true;
            else {
                $this->addError('id', 'Limita de elevi din această clasă a fost atinsă. Contactați administratorul site-ului pentru a modifica această limitare.');
                return false;
            }
        } else
            return false;
    }

    protected function afterDelete() {
        Account::model()->findByAttributes(array('type' => Account::TYPE_STUDENT, 'info' => $this->id))->delete();
        Parents::model()->findByPk($this->parent)->delete();
    }

    /**
     * @param int $class Class id
     * @return array with user ids in the specified class
     */
    public static function getByClass($class) {
        $students = Student::model()->findAllByAttributes(array('class' => $class), array('select' => 'id'));
        $return = array();
        foreach ($students as $student) {
            $return[] = $student->id;
        }
        return $return;
    }

}