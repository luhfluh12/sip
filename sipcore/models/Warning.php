<?php

/**
 * This is the model class for table "warnings".
 *
 * The followings are the available columns in table 'warnings':
 * @property integer $id
 * @property integer $student
 * @property integer $sent
 * @property integer $added
 * @property string $json
 * @property Student $rStudent
 */
class Warning extends CActiveRecord {
    const DRAFT_TIME=259200; // 3 days
    const QUIET_TIME=604800; // 7 days

    /**
     * Returns the static model of the specified AR class.
     * @return Warning the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'warnings';
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rStudent' => array(self::BELONGS_TO, 'Student', 'student'),
        );
    }

    /**
     * Returns an array with the SipWarnings for the given event.
     * @param string $event
     * @return array Array of events or empty array if the event does not exist
     */
    public static function getWarnings($event) {
        $warnings = array(
            'addMark' => array('WLowAverage'),
            'addAbsence' => array('WAbsences'),
        );
        return isset($warnings[$event]) ? $warnings[$event] : array();
    }

    public static function verify($event, $student) {
        $command = Yii::app()->db->createCommand("SELECT sent FROM warnings WHERE sent!=0 AND student=:st ORDER BY sent DESC LIMIT 1");
        $timelimit = (int) $command->queryScalar(array(':st' => $student));
        $draft = self::model()->find(array(
                    'condition' => 'sent=0 AND student=:student',
                    'params' => array(':student' => $student)
                ));
        $warnings = self::getWarnings($event);
        if ($draft !== null) {
            $save = false;
            foreach ($warnings as $warning) {
                if (!isset($this->json[$warning])) {
                    $new = $warning::check($student, $timelimit);
                    if ($new !== false) {
                        $this->json[$warning] = $new;
                        if ($save === false)
                            $save = true;
                    }
                }
            }
            if ($save === true)
                $draft->save();
        } else {
            $problems = array();
            foreach ($warnings as $warning) {
                $new = $warning::check($student, $timelimit);
                if ($new !== false)
                    $problems[$warning] = $new;
            }
            if (!empty($problems)) {
                $draft = new Warning;
                $draft->student = $student;
                $draft->json = $problems;
                $draft->save();
            }
        }
    }

    protected function afterFind() {
        parent::afterFind();
        $this->json = json_decode($this->json);
    }

    protected function beforeSave() {
        if (parent::beforesave()) {
            $this->json = json_encode($this->json);
            if ($this->isNewRecord)
                $this->added = time();
            return true;
        } else
            return false;
    }

}