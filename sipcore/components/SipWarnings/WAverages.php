<?php
/**
 * Checks if the student is failing at a subject
 * -- this is a work in progres; not used --
 */
class WAverages {
    const CORIGENTA=4.5;

    /**
     * Checks if from $timelimit to present, this problem happened
     * @param integer $student The student ID
     * @param integer $timelimit The timelimit
     * @param integer $subject The subject ID
     * @return mixed Array to be stored or FALSE if it does not apply 
     */

    public static function check($student, $timelimit, $stored, $subject) {
        $corigente = Chart::model()->findAll(array(
            'condition'=>'added>=:timelimit AND average<:corig AND subject=:subj AND student=:student',
            'params'=>array(
                ':timelimit'=>$timelimit,
                ':student'=>$student,
                ':subj'=>$subject,
                ':corig'=>self::CORIGENTA,
            ),
            'select'=>'average',
            'order'=>'added DESC, date DESC',
        ));

        if ($absences > self::ABSENCES_ALLOWED)
            return array();
        return false;
    }

    /**
     * The sotred data
     * @param array $stored
     * @return mixed Updated array to be stored and rendered or an empty value if there are problems
     */
    public static function recheck($student, $timelimit, $stored) {
        
    }
    /**
     * Render the problem to be human readable.
     * @param array $stored
     * @return string The problem, renderd properly.
     */
    public static function render($stored) {
        return 'Copilul dvs. are ' . $stored . ' absențe nemotivate noi.';
    }

}
