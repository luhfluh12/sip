<?php
/**
 * Checks if the student has too many unauthorized absences
 * @author vlad
 */
class WAbsences {
    const ABSENCES_ALLOWED=8;
    
    /**
     * Checks if from $timelimit to present, this problem happened
     * @param integer $student The student ID
     * @param integer $timelimit The timelimit
     * @return mixed Array to be stored or FALSE if it does not apply 
     */
    public static function check($student, $timelimit) {
        
    }
    
    /**
     * The problem renderd for humans.
     * @param array $stored
     * @return string The problem, renderd properly. 
     */
    public static function render($stored) {
        return 'Copilul dvs. are o problemă.';
    }
}
