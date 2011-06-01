<?php

class Schoolitem extends CActiveRecord
{
    public function validateSchoolyear($checkSemester=true) {
        $schoolyear=Schoolyear::model()->findByDate(time());
        if ($schoolyear===null) {
            $this->addError('date', 'Anul școlar nu a început încă sau nu există în baza de date.');
            return false;
        }
        $sem = Schoolyear::model()->getSemesterByDate(time(), $schoolyear->change);
        if ($checkSemester===false || ($sem === 1 && $this->date >= $schoolyear->start && $this->date <= $schoolyear->change)
           ||($sem===2 && $this->date >= $schoolyear->change && $this->date <= $schoolyear->end)) {

            $this->schoolyear=$schoolyear->id;
            $this->semester=$sem;
            return true;
        } else {
            $this->addError('date', 'Data nu este în semestrul curent.');
            return false;
        }
    }
    /**
     * Retrives the list with items (marks or absences).
     * @param int $student Student ID
     * @param int $subject 
     * @param int $datestamp Year (yyyy format) of the beggining of this year.
     * @return array of AR Marks
     */
    public function findByStudentAndSubject($student,$subject,$datestamp=false) {
        return $this->findAll($this->getSASCriteria($student,$subject,$datestamp));
    }
    public function getSASCriteria ($student,$subject,$datestamp=false) {
        if ($datestamp===false) $datestamp=time();
        $year = Schoolyear::yearByMonth(date('n'), date('Y'));
        return array(
            'condition'=>'student=:st AND subject=:su AND schoolyear=:y AND semester=:se',
            'order'=>'date ASC',
            'params'=>array(
                ':st'=>$student,
                ':su'=>$subject,
                ':y'=>$year,
                ':se'=>Schoolyear::model()->getSemesterByDate($datestamp),
            ),
        );
    }
}


?>
