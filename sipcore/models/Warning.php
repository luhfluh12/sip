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
            'addMark' => array('WAverages'),
            'addAbsence' => array('WAbsences'),
        );
        return isset($warnings[$event]) ? $warnings[$event] : array();
    }

    /**
     * Checks and renders the drafts into SMSes.
     * @param bool $force Whether to force the rendering or not.
     */
    public static function checkDrafts($force) {
        $time = time();
        $command = Yii::app()->db->createCommand('SELECT MAX(sent) FROM `warnings` WHERE student=:st');
        $drafts = self::model()->findAll('sent=0 AND added<=:a', array(':a' => $time - ($force === true ? 0 : self::DRAFT_TIME)));
        foreach ($drafts as $draft) {
            $lastSent = $command->queryScalar(array(':st' => $draft->student));
            if ($lastSent <= $time - self::QUIET_TIME || $force === true) {
                // recheck all the stored problems
                $newProblems = array();
                $message = '';
                foreach ($draft->json as $w => $values) {
                    if (class_exists($w)) {
                        $new = $w::validate($draft->student, $lastSent, $values);
                        if ($new !== false) {
                            $newProblems[$w] = $new;
                            $message .= $w::render($new);
                        }
                    }
                }
                // if there are still problems, render and send
                if (!empty($newProblems)) {
                    $draft->sent = $time;
                    $sms = new Sms;
                    $sms->account = $draft->rStudent->parent;
                    $sms->message = $message;
                    $sms->hour1 = $draft->rStudent->rParent->sms_hour1;
                    $sms->hour2 = $draft->rStudent->rParent->sms_hour2;
                    $sms->queue(false);
                }
                // save the changes
                $draft->json = $newProblems;
                $draft->save();
            }
        }
    }

    public static function verify($event, $student, $subject=false) {
        $warnings = self::getWarnings($event);
        if (empty($warnings))
            return false;
        $command = Yii::app()->db->createCommand("SELECT sent FROM warnings WHERE sent!=0 AND student=:st ORDER BY sent DESC LIMIT 1");
        $timelimit = (int) $command->queryScalar(array(':st' => $student));
        $draft = self::model()->find(array(
            'condition' => 'sent=0 AND student=:student',
            'params' => array(':student' => $student),
                ));
        if ($draft !== null) {
            $save = false;
            if (!is_array($draft->json))
                $draft->json = array();
            foreach ($warnings as $warning) {
                if (class_exists($warning)) {
                    $new = $warning::check($student, $subject, $timelimit, isset($draft->json[$warning]) ? $draft->json[$warning] : false);
                    if ($new !== false) {
                        if (isset($draft->json[$warning]))
                            $draft->json[$warning] = $new;
                        else
                            $draft->json += array($warning => $new);
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
                if (class_exists($warning)) {
                    $new = $warning::check($student, $subject, $timelimit, false);
                    if ($new !== false)
                        $problems[$warning] = $new;
                }
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
        $this->json = json_decode($this->json, true);
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