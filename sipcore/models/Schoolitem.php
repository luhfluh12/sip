<?php

class Schoolitem extends CActiveRecord {

    /**
     * Retrives the list with items (marks or absences).
     * @param integer $student Student ID
     * @param integer $subject Subject ID
     * @return array of Marks/Absences Models
     */
    public function findByStudentAndSubject($student, $subject) {
        return $this->findAll(array(
            'condition'=>'student=:st AND subject=:su AND date!=0',
            'order'=>'date ASC, added ASC',
            'params'=>array(':st'=>$student,':su'=>$subject),
        ));
    }

    /**
     *
     * @param integer $date
     * @return boolean Whether the date is in the current semester 
     */
    public function validateDate($date) {
        $now = time();
        // if the date is in the future
        if ($date > $now)
            return false;
        // if the date is in another schoolyear
        if (Schoolyear::thisYear($date) !== Schoolyear::thisYear($now))
            return false;
        // if the date is in another semester
        if (Schoolyear::thisSemester($date) !== Schoolyear::thisSemester($now))
            return false;
        return true;
    }

}

?>
