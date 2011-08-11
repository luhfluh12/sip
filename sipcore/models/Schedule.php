<?php

/**
 * This is the model class for table "schedule".
 *
 * The followings are the available columns in table 'schedule':
 * @property integer $class
 * @property integer $hour
 * @property integer $subject
 * @property integer $weekday
 */
class Schedule extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Schedule the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function primaryKey() {
        return array('weekday', 'hour', 'class');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'schedule';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('class, hour, subject, weekday', 'required'),
            array('class, hour, subject, weekday', 'numerical', 'integerOnly' => true),
            array('weekday', 'in', 'range' => array(1, 2, 3, 4, 5)),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('class, hour, subject, weekday', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rSubject' => array(self::BELONGS_TO, 'Subject', 'subject'),
            'rClass' => array(self::BELONGS_TO, 'Classes', 'class'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'class' => 'Clasă',
            'hour' => 'Oră',
            'subject' => 'Materie',
            'weekday' => 'Zi',
        );
    }

    /**
     *
     * @param integer $class The IF of the class to search in
     * @param boolean $name whether to get subject names or IDs
     * @return array $schedule[weekday][hour]= subject->name or subject->id or FALSE if it fails
     */
    public static function getClassSchedule($class, $name=true) {
        $search = Schedule::model()->with('rSubject')->findAllByAttributes(array('class' => (int) $class), array('order' => 'weekday ASC, hour ASC'));
        if (empty($search))
            return false;
        $result = array();
        foreach ($search as $item) {
            $result[$item->weekday][$item->hour] = $name === true ? $item->rSubject->name : $item->rSubject->id;
        }
        if (empty($result))
            return false;
        return $result;
    }

    /**
     * Get the romanian name of the given weekday.
     * @param integer $dayNo date('w')
     * @return mixed String with the name or FALSE if the $dayNo is invalid 
     */
    public static function getWeekday($dayNo) {
        $days = self::getWeekdays(true);
        return isset($days[$dayNo]) ? $days[$dayNo] : false;
    }

    /**
     * Get an array of romanian weekday names.
     * Type of array: (date('w')=>'Romanian Name')
     * @param boolean $weekend Whether to get the weekend
     * @return array The weekdays
     */
    public static function getWeekdays($weekend=false) {
        if ($weekend === true)
            return array(
                0 => 'Duminică',
                1 => 'Luni',
                2 => 'Marți',
                3 => 'Miercuri',
                4 => 'Joi',
                5 => 'Vineri',
                6 => 'Sâmbătă',
            );
        return array(
            1 => 'Luni',
            2 => 'Marți',
            3 => 'Miercuri',
            4 => 'Joi',
            5 => 'Vineri',
        );
    }

    /**
     * Check if a student has a subject in a weekday.
     * @param integer $subject Subject ID
     * @param integer $student Student ID
     * @param integer $date Datestamp to check
     * @return integer No. of hours that student has at the given date (usually 0 or 1)
     */
    public static function hasStudentSubject($subject, $student, $date) {
        $student = Student::model()->findByPk($student, array('select' => 'class'));
        if ($student === null)
            return false;
        return (int) Schedule::model()->count('subject=:su AND class=:cl AND weekday=:we', array(
            ':su' => $subject,
            ':cl' => $student->class,
            ':we' => date('w', $date),
        ));
    }

    public static function saveClassSchedule($schedule, $class) {
        if (!is_array($schedule))
            return false;
        foreach ($schedule as $weekday => $temp) {
            if (!is_array($temp))
                return false;
            foreach ($temp as $hour => $subject) {
                $scheduleModel = Schedule::model()->findByPk(array('weekday' => (int) $weekday, 'class' => (int) $class, 'hour' => (int) $hour));
                if (empty($subject)) {
                    if ($scheduleModel !== null)
                        $scheduleModel->delete();
                } elseif ($scheduleModel === null) {
                    $scheduleModel = new Schedule;
                    $scheduleModel->weekday = (int) $weekday;
                    $scheduleModel->hour = (int) $hour;
                    $scheduleModel->class = (int) $class;
                    $scheduleModel->subject = Subject::model()->getSubjectId($subject);
                    $scheduleModel->save();
                } elseif ($scheduleModel->subject !== $subject) {
                    $scheduleModel->subject = Subject::model()->getSubjectId($subject);
                    $scheduleModel->save();
                }
            }
        }
        return true;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('class', $this->class);
        $criteria->compare('hour', $this->hour);
        $criteria->compare('subject', $this->subject);
        $criteria->compare('weekday', $this->weekday);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}