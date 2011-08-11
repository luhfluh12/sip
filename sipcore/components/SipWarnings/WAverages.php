<?php

/**
 * Checks if the student has too many unauthorized absences
 * @author vlad
 */
class WAverages {
    const CORIGENTA=4.5;

    /**
     * Checks if from $timelimit to present, this problem happened
     * @param integer $student The student ID
     * @param integer $timelimit The timelimit
     * @return mixed Array to be stored or FALSE if it does not apply 
     */
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

    /**
     * Render the problem to be human readable.
     * @param array $stored
     * @return string The problem, renderd properly.
     */
    public static function render($stored) {
        return 'Copilul dvs. are ' . $stored . ' absențe nemotivate noi.';
    }

}