<?php

/**
 * This is the model class for table "absences".
 *
 * The followings are the available columns in table 'absences':
 * @property integer $id
 * @property string $date
 * @property integer $subject
 * @property string $added
 * @property integer $authorized
 * @property Student $rStudent
 * @property Subject $rSubject
 */
class Absence extends Schoolitem {
    const STATUS_AUTH=1;
    const STATUS_UNAUTH=2;

    /**
     * Returns the static model of the specified AR class.
     * @return Absence the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'absences';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date, subject, student', 'required'),
            array('subject', 'numerical', 'integerOnly' => true),
            array('authorized', 'numerical', 'integerOnly' => true, 'allowEmpty' => true),
            array('subject', 'exist', 'className' => 'Subject', 'attributeName' => 'id', 'allowEmpty' => false),
            array('student', 'exist', 'className' => 'Student', 'attributeName' => 'id', 'allowEmpty' => false),
            array('student, subject, date', 'unsafe', 'on' => 'update'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rStudent' => array(self::BELONGS_TO, 'Student', 'student'),
            'rSubject' => array(self::BELONGS_TO, 'Subject', 'subject'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'date' => 'Dată',
            'subject' => 'Materie',
            'added' => 'Adăugat în SIP',
            'authorized' => 'Motivată',
        );
    }

    /**
     *
     * @param start $start used in strtotime()
     * @param name $end used in strtotime(). If empty just one day is added
     * @param CActiveRecord $student Student model
     * @return array the added absences and a special key ['added']=(int) no of absences added
     * @return false if it fails
     */
    public static function saveInterval($start, $end, $student) {
        $start = strtotime($start);
        $added = time();
        $end = empty($end) ? $start : strtotime($end);
        $schedule = Schedule::getClassSchedule($student->class, false);
        $abs = 0;
        $day = 60 * 60 * 24;
        $returnArray = array();
        $query = "INSERT INTO " . self::model()->tableName() . "
                (date, subject, student, added, authorized) VALUES
                (:date, :subject, :student, :added, :authorized)";
        $command = Yii::app()->db->createCommand($query);
        $schoolyear = false;
        while ($start <= $end) {
            $weekday = date('w', $start);
            $_year = Schoolyear::yearByMonth(date('n', $start), date('Y', $start));
            if ($schoolyear !== $_year) {
                $schoolyear = $_year;
                $semester = Schoolyear::model()->getSemesterByDate($start);
            }
            if (isset($schedule[$weekday]) && is_array($schedule[$weekday])) {
                foreach ($schedule[$weekday] as $subject) {
                    $command->execute(array(
                        ':date' => $start,
                        ':subject' => $subject,
                        ':authorized' => self::STATUS_UNAUTH,
                        ':added' => $added,
                        ':student' => $student->id,
                    ));
                    if (isset($returnArray[$subject]) && is_array($returnArray[$subject]))
                        $returnArray[$subject] = array_merge($returnArray[$subject], array($abs => date('d F Y', $start)));
                    else
                        $returnArray[$subject] = array($abs => date('d F Y', $start));
                    $abs++;
                }
            }
            // next day
            $start += $day;
        }
        if ($abs === 0)
            return false;
        $returnArray['added'] = $abs;
        return $returnArray;
    }

    /**
     * Calculates the number of absences that can be added in a day.
     * It is the difference between the no of hours of the subject and the current no of absences at the specified date.
     * @param integer $student
     * @param integer $subject
     * @param integer $date
     * @return integer Number of absences that can be added. 
     */
    public static function howMuch($student, $subject, $date) {
        $total = Schedule::hasStudentSubject($subject, $student, $date);
        if ($total === 0)
            return 0;
        $yet = self::model()->count('student=:student AND subject=:subject AND date=:date', array(
                    ':student' => $subject,
                    ':subject' => $subject,
                    ':date' => $date,
                ));
        if ($yet == $total)
            return 0;
        else
            return $total - $yet;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->rSubject->show == 0)
                return false;

            if ($this->isNewRecord) {
                $this->date = strtotime($this->date);
                // check if the date is in schooltime (and not vacantion)
                $break = Breaks::isInBreak($this->date);
                if ($break !== false) {
                    $this->addError('date', 'Nu poți adăuga note în ' . $break);
                    return false;
                }
                // allowing 2 absences to be added if the subject is twice in the schedule of the day
                if (!self::howMuch($this->student, $this->subject, $this->date)) {
                    $this->addError('date', 'Nu (mai) poți adăuga absențe în această zi.');
                    return false;
                }
            }

            if ($this->authorized != Absence::STATUS_AUTH)
                $this->authorized = Absence::STATUS_UNAUTH;

            $this->added = time();
            return true;
        } else
            return false;
    }

}