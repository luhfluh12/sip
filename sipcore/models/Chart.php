<?php

/**
 * This is the model class for table "averages".
 *
 * The followings are the available columns in table 'averages':
 * @property integer $id
 * @property integer $subject
 * @property integer $student
 * @property integer $type
 * @property integer $date
 * @property integer $added
 * @property Subjects $rSubject
 * @property Students $rStudnet
 */
class Chart extends CActiveRecord {
    const SMS_POINTS = 3; // max points the avg can decrase without sms
    const LOW_AVERAGE = 4.5; // minimum allowed average to pass the class

    /**
     * Returns the static model of the specified AR class.
     * @return Chart the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'chart';
    }

    /**
     * @return array validation rules for model attributes.
     */

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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'subject' => 'Materie',
            'student' => 'Elev',
            'date' => 'Data din catalog',
        );
    }

    /**
     * Updates averages.
     * @param integer $student
     * @param integer $subject
     * @param integer $schoolyear
     * @param integer $semester
     * @param integer $date - the timelimit
     * @return integer 0 if success, no. of fails instead
     */
    public static function updateAverages($student, $subject, $date) {
        $charts = Chart::model()->findAll(array(
                    'condition' => 'date>:date AND student=:student AND subject=:subject',
                    'params' => array(
                        ':student' => $student,
                        ':subject' => $subject,
                        ':date' => $date,
                    ),
                ));
        //var_dump($averages);
        $ret = 0;
        foreach ($charts as $chart) {
            $chart->updateAvg();
            if (!$chart->save())
                $ret++;
        }
        return $ret;
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
        $criteria->compare('subject', $this->subject);
        $criteria->compare('student', $this->student);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function updateAvg() {
        $this->average = Mark::model()->getAverageStudentSubject($this->student, $this->subject, $this->date);
    }

    protected function afterSave() {
        parent::afterSave();
        /*
        $lastAverage = $this->find(array(
                    'condition' => 'student=:student AND subject=:subject AND added<:added',
                    'params' => array(
                        ':student' => $this->student,
                        ':subject' => $this->subject,
                        ':added' => $this->date,
                    ),
                ));
        if ($lastAverage !== null) { // don't create any SMS draft at first mark
            if ($lastAverage->average - $this->average >= Chart::SMS_POINTS ||
                    $lastAverage->average >= Chart::LOW_AVERAGE && $this->average < Chart::LOW_AVERAGE) {
                return Sms::model()->saveDraft($this->student, Sms::ADD_AVERAGE_LOW, array(
                    'subject' => $this->subject,
                    'datestamp' => $this->added,
                ));
            }
        }
        */
        return true;
    }

}