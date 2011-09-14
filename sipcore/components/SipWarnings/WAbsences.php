<?php
/**
 * Checks if the student has too many unauthorized absences
 */
class WAbsences {
    const ABSENCES_ALLOWED=8;

    public static function check($student, $timelimit) {
        $absences = (int) Absence::model()->count('added>:added AND student=:st AND authorized=:auth', array(
                    ':added' => $timelimit,
                    ':st' => $student,
                    ':auth' => Absence::STATUS_UNAUTH,
                ));
        if ($absences > self::ABSENCES_ALLOWED)
            return $absences;
        return false;
    }

/*    public static function recheck($student, $timelimit, $values) {
        return self::check($student, $timelimit);
    }*/
    
    public static function render($stored) {
        return 'Copilul dvs. are ' . $stored . ' absențe nemotivate noi.';
    }

}
