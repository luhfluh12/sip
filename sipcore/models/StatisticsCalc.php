<?php
class StatisticsCalc extends CActiveRecord {
    protected function calculate($generator,$class,$schoolyear,$semester) {
        $ex = 'generator'.ucwords($generator);
        return $this->$ex($class,$schoolyear,$semester);
    }
    /**
     * every generator will receive three parameters, and must be defined as follows
     * 
     * protected function generatorGeneratorName ($class, $schoolyear, $semester) {
     *  return false; // if fail
     *  return (int); // if succes; it MUST return a number
     * }
     */
    
    protected function generatorTotalAbsences ($class, $year, $sem) {
        $students = Student::getByClass($class);
        $query = 'SELECT COUNT(id) FROM `absences` WHERE student IN ('.implode(',',$students).')';
        return Yii::app()->db->createCommand()->setText($query)->queryScalar();
    }

    protected function generatorTotalAuthAbsences ($class, $year, $sem) {
        $students = Student::getByClass($class);
        $query = 'SELECT COUNT(id) FROM `absences` WHERE student IN ('.implode(',',$students).') AND authorized=:auth';
        return Yii::app()->db->createCommand()->setText($query)->queryScalar(array(':auth'=>Absence::STATUS_AUTH));
    }

    protected function generatorTotalUnauthAbsences ($class, $year, $sem) {
        $students = Student::getByClass($class);
        $query = 'SELECT COUNT(id) FROM `absences` WHERE student IN ('.implode(',',$students).') AND authorized=:auth';
        return Yii::app()->db->createCommand()->setText($query)->queryScalar(array(':auth'=>Absence::STATUS_UNAUTH));
    }
    protected function generatorStudentAverages ($class, $year, $sem) {
        $students = Student::getByClass($class);
        foreach ($students as $student) {
            for ($i=1;i<10;$i=$i+2) {
                $j=$i+1;
                $avg = Chart::model()->find(array(
                    'student'=>$student,
                    ''
                ));
            }
        }
    }

}