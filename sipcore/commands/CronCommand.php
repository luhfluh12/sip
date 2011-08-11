<?php

class CronCommand extends CConsoleCommand {

    public function actionSend() {
        echo '[', date('j F Y H:i:s'), '] SIP SMS sending CronJob: ', Sms::sendCron(), " sent.\n";
    }

    public function actionWarnings() {
        echo '[', date('j F Y H:i:s'), '] SIP warning checks: ', Warning::checkDrafts(), "\n";
    }

    public function actionEndSemester() {
        $classes = Classes::model()->with('rSubjects')->findAll(array('select' => '`rSubjects`.id, `Classes`.id'));
        $schoolyear = Schoolyear::thisYear(time());
        $sem = Schoolyear::thisSemester(time());
        $getStudents = Yii::app()->db->createCommand("SELECT id FROM students WHERE class=:class");
        $saveAbsenceHistory = Yii::app()->db->createCommand("INSERT INTO `absencesHistory` (student, year, semester, auth, unauth) VALUES (:student, $schoolyear, $sem, :auth, :unauth)");
        if ($sem === 1) {
            $insertAverage = Yii::app()->db->createCommand("INSERT INTO `averages` (student, subject, sem1, year) VALUES (:student, :subject, :sem, $schoolyear)");
        } else {
            $insertAverage = Yii::app()->db->createCommand("INSERT INTO `averages` (student, subject, sem2, final, year) VALUES (:student, :subject, :sem, :sem, $schoolyear)");
            $updateAverage = Yii::app()->db->createCommand("UPDATE `averages` SET sem2=:sem, final=(sem1+sem2/2) WHERE id=:avgid");
            $getAverage = Yii::app()->db->createCommand("SELECT id FROM `averages` WHERE student=:student AND year=$schoolyear AND subject=:subject");
        }

        $classesIds = array();
        foreach ($classes as $class) {
            $classesIds[] = $class->id;
            $students = $getStudents->queryColumn(array(':class' => $class->id));
            foreach ($students as $student) {
                foreach ($class->rSubjects as $subject) {
                    $newAvg = Mark::getAverageStudentSubject($student, $subject->id);
                    if ($newAvg) {
                        if ($sem === 1) {
                            $insertAverage->execute(array(':student' => $student, ':subject' => $subject->id, ':sem' => $newAvg));
                        } else {
                            // search for the existing average
                            $avgid = $getAverage->queryScalar(array(':student' => $student, ':subject' => $subject->id));
                            if ($avgid) {
                                // update if available
                                $updateAverage->execute(array(':sem' => $newAvg, ':avgid' => $avgid));
                            } else {
                                $insertAverage->execute(array(':student' => $student, ':subject' => $subject->id, ':sem' => $newAvg));
                            }
                        }
                    }
                }

                $saveAbsenceHistory->execute(array(
                    ':auth' => Absence::model()->countByAttributes(array(
                        'student' => $student,
                        'authorized' => Absence::STATUS_AUTH,
                    )),
                    ':unauth' => Absence::model()->countByAttributes(array(
                        'student' => $student,
                        'authorized' => Absence::STATUS_UNAUTH,
                    )),
                    ':student' => $student,
                ));
            }
        }

        Yii::app()->db->createCommand('TRUNCATE TABLE `absences`');
        Yii::app()->db->createCommand('TRUNCATE TABLE `marks`');
    }

}

