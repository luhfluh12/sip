<?php

/**
 * This is the model class for table "averages".
 *
 * The followings are the available columns in table 'averages':
 * @property integer $id
 * @property integer $student
 * @property integer $subject
 * @property integer $year
 * @property integer $sem1
 * @property integer $sem2
 * @property integer $exam
 * @property double $final
 * @property Subject $rSubject
 * @property Student $rStudent
 */
class Average extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Average the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'averages';
    }

    public function rules() {
        return array(
            array('student', 'exist', 'className' => 'Student', 'attributeName' => 'id'),
            array('subject', 'exist', 'className' => 'Subject', 'attributeName' => 'id'),
            array('sem1, sem2', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => 10, 'allowEmpty' => true),
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
            'rStudent' => array(self::BELONGS_TO, 'Student', 'student'),
        );
    }

    /**
     * Get the Average model.
     * @param integer $student
     * @param integer $subject
     * @param integer $year
     * @return Average The model. 
     */
    public function findByStudentAndSubject($student, $subject, $year) {
        return $this->findByAttributes(array('student' => $student, 'subject' => $subject, 'year' => $year));
    }

    /**
     * Returns the average at "Purtare" of the current semester
     * @param integer $student
     * @return integer The requested mark
     */
    public static function getPurtare($student) {
        $time = time();
        $purtare = Average::model()->findByStudentAndSubject($student, Subject::ID_PURTARE, Schoolyear::thisYear($time));
        if ($purtare === null)
            return 10;
        if (Schoolyear::thisSemester($time) === 1)
            return $purtare->sem1;
        else
            return $purtare->sem2;
    }

    /**
     * Saves a new average at "purtare" in the current semester
     * @param integer $student
     * @param integer $mark
     * @return boolean Whether the save succeeded 
     */
    public static function setPurtare($student, $mark) {
        if ($mark < 1)
            return false;
        $purtare = Average::model()->findByStudentAndSubject($student, Subject::ID_PURTARE, Schoolyear::thisYear(time()));
        var_dump($purtare);
        $sem = Schoolyear::thisSemester(time());
        if ($purtare === null) {
            $purtare = new Average;
            $purtare->student = $_POST['Mark']['student'];
            $purtare->year = Schoolyear::thisYear(time());
            $purtare->subject = Subject::ID_PURTARE;
        }
        if ($sem === 1) {
            if ($purtare->sem1 == $mark)
                return true;
            $purtare->sem1 = $mark;
            // set the default value
            $purtare->sem2 = 10;
        } else {
            if ($purtare->sem2 == $mark)
                return true;
            $purtare->sem2 = $mark;
        }
        return $purtare->save();
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->exam) {
                $this->final = $this->exam;
            } elseif ($this->sem1 && $this->sem2) {
                $this->final = (float) ($this->sem1 + $this->sem2) / 2;
            }
            return true;
        } else
            return false;
    }

}