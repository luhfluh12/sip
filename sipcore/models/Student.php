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

    private $_purtare = null;

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
            array('name', 'required'),
            array('name', 'length', 'max' => 200),
            array('name', 'filter', 'filter' => array('CHtml', 'encode')),
            array('class', 'exist', 'className'=>'Classes', 'attributeName'=>'id'),
            array('school', 'exist', 'className'=>'School', 'attributeName'=>'id'),
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
            'rMarks' => array(self::HAS_MANY, 'Mark', 'student'),
            'rSchool' => array(self::BELONGS_TO, 'School', 'school'),
            'rSms' => array(self::HAS_MANY, 'Sms', 'student', 'order' => 'sent, added DESC', 'on' => 'status=' . Sms::STATUS_SENT),
            'rSmses' => array(self::HAS_MANY, 'Sms', 'student', 'order' => 'sent, added DESC'),
            'rAverages' => array(self::HAS_MANY, 'Averages', 'student', 'order' => 'subject ASC, date ASC'),
            'rPurtare' => array(self::HAS_ONE, 'Averages', 'student', 'condition' => 'subject=' . Subject::ID_PURTARE)
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

    public function getPurtare() {
        if ($this->_purtare)
            return $this->_purtare;
        $this->_purtare = $this->rPurtare;
        if (!$this->_purtare) {
            $pu = new Averages;
            $pu->student = $this->id;
            $pu->average = 10;
            $pu->subject = Subject::ID_PURTARE;
            $schoolyear = Schoolyear::model()->findByDate();
            //if (!$schoolyear) return false;
            $pu->schoolyear = $schoolyear->id;
            $pu->semester = $schoolyear->getSemesterByDate(time(), $schoolyear->change);
            $pu->added = time();
            $pu->date = time();
            $pu->type = Averages::TYPE_OFFICIAL;
            $pu->save();

            $this->_purtare = $pu->average;
        } else
            $this->_purtare = $this->_purtare->average;
        return $this->_purtare;
    }

    public function setPurtare($newpurtare) {
        $purtare = $this->rPurtare;
        if ($purtare == null) {
            $this->getPurtare();
            $purtare = $this->rPurtare;
        }
        $purtare->average = $newpurtare;
        return $purtare->save();
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