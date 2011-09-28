<?php
/**
 * Checks if the student has too many unauthorized absences
 */
class WAbsences {
    const ABSENCES_ALLOWED=8;

    public static function check($student, $subject, $timelimit, $stored) {
        // if we have this problem already stored, no need to do the calculations again
        if ($stored!==false)
            return false;
        
        $absences = (int) Absence::model()->count('added>:added AND student=:st AND authorized=:auth', array(
                    ':added' => $timelimit,
                    ':st' => $student,
                    ':auth' => Absence::STATUS_UNAUTH,
                ));
        if ($absences > self::ABSENCES_ALLOWED)
            return $absences;
        
        return false;
    }

    public static function validate($student, $timelimit, $stored) {
        // force the check by acting like there are no datas stored
        return self::check($student, 0, $timelimit, false);
    }
    
    public static function render($stored) {
        return 'Copilul dvs. are ' . $stored . ' absențe nemotivate noi.';
    }

}
