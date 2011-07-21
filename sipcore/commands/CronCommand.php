<?php

class CronCommand extends CConsoleCommand {

    public function actionSend() {
        echo '[', date('j F Y H:i:s'), '] SIP SMS sending CronJob: ', Sms::sendCron(), " sent.\n";
    }

    public function actionWarnings() {
        echo '[', date('j F Y H:i:s'), '] SIP warning checks: ', Warning::checkDrafts(), "\n";
    }

    public function actionEnd($sem=1) {
        $classes = Classes::model()->with('rSubjects')->findAll(array('select' => '`rSubjects`.id, `Classes`.id'));
        $schoolyear = Schoolyear::thisYear(time());
        $getStudents = Yii::app()->db->createCommand("SELECT id FROM students WHERE class=:class");
        $saveAverage = Yii::app()->db->createCommand("INSERT INTO `averages` (student, subject, sem$sem, year) VALUES (:student, :subject, :sem, $schoolyear)");
        foreach ($classes as $class) {
            $students = $getStudents->queryColumn(array(':class' => $class->id));
            foreach ($students as $student) {
                foreach ($class->rSubjects as $subject) {
                    $saveAverage->execute(array(':student' => $student, ':subject' => $subject->id, ':sem' => Mark::getAverageStudentSubject($student, $subject->id)));
                }
                // store the no of absences
            }
        }
        // delete all remaining marks and absences
    }

}

