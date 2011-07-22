<?php

class SAbsences {

    public static function calculate($class, $year, $semester) {
        $students = Student::getByClass($class);
        $query = 'SELECT SUM(auth) as motivate, SUM(unauth) as nemotivate FROM `absencesHistory` WHERE student IN (' . implode(',', $students) . ') AND year=:year AND semester=:semester';
        $command = Yii::app()->db->createCommand($query);
        $data = $command->queryRow(true, array(':year' => $year, ':semester' => $semester));
        return array('auth' => (int)$data['motivate'], 'unauth' => (int)$data['nemotivate']);
    }

}
