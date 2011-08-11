<?php

/**
 * This is the model class for table "marks".
 *
 * The followings are the available columns in table 'marks':
 * @property integer $id
 * @property integer $student
 * @property integer $subject
 * @property integer $mark
 * @property integer $date
 * @property integer $added
 * @property Subject $rSubject 
 * @property Student $rStudent
 */
class Mark extends Schoolitem {

    /**
     * Returns the static model of the specified AR class.
     * @return Mark the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'marks';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('student, subject, mark', 'required'),
            array('date', 'required', 'on' => 'insert'),
            array('student, subject', 'numerical', 'integerOnly' => true),
            array('mark', 'numerical', 'integerOnly' => true, 'max' => 10, 'min' => 1),
            array('subject', 'exist', 'className' => 'Subject', 'attributeName' => 'id', 'allowEmpty' => false),
            array('student', 'exist', 'className' => 'Student', 'attributeName' => 'id', 'allowEmpty' => false),
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
     * Checks if the student already has a mark at this subject, at this date
     * @return boolean Whether the current mark is unique or not 
     */
    public function isUniqueMark() {
        return $this->exists('date=:da AND subject=:su AND student=:st', array(
            ':da' => $this->date,
            ':su' => $this->subject,
            ':st' => $this->student,
        )) === false;
    }

    public function deleteCurrentThesis($student, $subject) {
        if ($semester === false) {
            $semester = $this->currentSemester();
        }
        return $this->delete('date=:d AND student=:student AND subject=:subject', array(
            ':d' => 0,
            ':student' => $student,
            ':subject' => $subject,
        ));
    }

    /**
     * Get the thesis model.
     * @param integer $student
     * @param integer $subject
     * @return Mark The model of the thesis 
     */
    public function getCurrentThesis($student, $subject) {
        return $this->find(array(
            'condition' => 'date=0 AND student=:st AND subject=:su',
            'params' => array(
                ':st' => $student,
                ':su' => $subject,
            ),
        ));
    }

    /**
     * Get the thesis without creating a model.
     * @param integer $student
     * @param integer $subject
     * @return mixed The thesis or FALSE if it doesn't exist 
     */
    public static function getScalarThesis($student, $subject) {
        $query = "SELECT mark FROM `marks`
                WHERE student=:student
                AND subject=:subject
                AND date=0";
        $command = Yii::app()->db->createCommand($query);
        $command->bindValues(array(
            ':student' => $student,
            ':subject' => $subject,
        ));
        return $command->queryScalar();
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->rSubject->show == 0)
                return false;
            if ($this->isNewRecord)
                $this->added = time();
            if ($this->scenario === 'thesis') {
                $this->date = 0;
            } else {
                $this->date = strtotime($this->date);
            }
            if ($this->scenario !== 'thesis') {
                // only one mark per day
                if ($this->isUniqueMark() === false) {
                    $this->addError('date', 'Elevul mai are o notă în această zi.');
                    return false;
                }

                // check the date to be in the current semester and schoolyear
                if ($this->validateDate($this->date) === false) {
                    $this->addError('date', 'Data nu este în semestrul și anul școlar curent sau este în viitor.');
                    return false;
                }
                // check if the date is in the schedule
                if (Schedule::hasStudentSubject($this->subject, $this->student, $this->date) === 0) {
                    $this->addError('date', 'Această materie nu este în orar ' . strtolower(Schedule::getWeekday(date('w', $this->date))));
                    return false;
                }
                // check if the date is in schooltime (and not vacantion)
                $break = Breaks::isInBreak($this->date);
                if ($break !== false) {
                    $this->addError('date', 'Nu poți adăuga note în ' . $break);
                    return false;
                }
            }
            return true;
        } else
            return false;
    }

    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $chart = new Chart;
            $chart->student = $this->student;
            $chart->subject = $this->subject;
            $chart->added = $this->added;
            $chart->date = ($this->date ? $this->date : $this->added);
            $chart->average = $this->getAverageStudentSubject($this->student, $this->subject, ($this->date ? $this->date : false));
            if ($chart->save() === false)
                return false;
        } else {
            // thesis can be updated
            $chart = Chart::model()->find(array(
                        'select' => 'id',
                        'condition' => 'student=:student AND subject=:subject AND date=:date',
                        'params' => array(
                            'student' => $this->student,
                            'subject' => $this->subject,
                            'date' => $this->added,
                        ),
                    ));
            if ($chart) {
                $chart->average = $this->getAverageStudentSubject($this->student, $this->subject, $this->added);
                if (!$chart->update(array('average'))) {
                    return false;
                }
            } else {
                return false;
            }
        }

        Chart::updateAverages($this->student, $this->subject, ($this->date ? $this->date : $this->added));
        return true;
    }

    protected function afterDelete() {
        parent::afterDelete();
        Chart::model()->deleteAllByAttributes(array(
            'student' => $this->student,
            'subject' => $this->subject,
            'date' => ($this->date ? $this->date : $this->added),
        ));
        Chart::updateAverages($this->student, $this->subject, ($this->date ? $this->date : $this->added));
        return true;
    }

    /**
     * Calculates the average mark.
     * If $timelimit is given, the average is calculated at that tisme.
     * @param integer $student
     * @param integer $subject
     * @param integer $timelimit
     * @return float The average 
     */
    public static function getAverageStudentSubject($student, $subject, $timelimit=false) {
        $query = "SELECT AVG(mark) AS avg FROM marks
                WHERE student=:student
                AND subject=:subject";
        if ($timelimit !== false)
            $query.=" AND date<=:t";
        $command = Yii::app()->db->createCommand($query);
        $command->bindValues(array(
            ':student' => $student,
            ':subject' => $subject,
        ));
        if ($timelimit !== false)
            $command->bindValue(':t', $timelimit);
        $avg_marks = $command->queryScalar();
        $thesis = self::getScalarThesis($student, $subject);
        if ($thesis) {
            return (double) (($avg_marks * 3) + $thesis) / 4;
        }
        return $avg_marks;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'student' => 'Elev',
            'subject' => 'Materie',
            'mark' => 'Notă',
            'date' => 'Data din catalog:',
            'added' => 'Data adăugării în SIP:',
        );
    }

}