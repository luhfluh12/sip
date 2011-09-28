<?php

class WExample {

    /**
     * Make the actual check and returns what to be stored or false if there is no problem
     * @param integer $student Student ID
     * @param integer $subject Subject ID
     * @param integer $timelimit The timestamp of the last SMS sent.
     * @param mixed $stored This is what is already stored. FALSE if this is a new problem.
     * @return mixed What to be stored for this particular problem. 
     */
    public static function check($student, $subject, $timelimit, $stored) {
        // If this problem needs the subject to be stored, make sure this function returns it,
        // as the subject is not being stored in any other kind.
        //
        // make some calculations
        // if there are problems:
        //    return WhatToBeStored;
        // return false;
    }

    /**
     * This function must remake the problems; it is called right after rendering
     * @param type $student
     * @param type $timelimit
     * @param type $stored
     * @return mixed Returns the same thing as check, but has more stuff to do...
     */
    public static function validate($student, $timelimit, $stored) {
        // checks if all the stored values are still a problem,
        // usually the following code does the trick
        return WExample::check($student, 0, $timelimit, $stored);
    }
    
    /**
     * Returns the problem in a human-readable way 
     * @param string $stored 
     */
    public static function render($stored) {
        // sample code:
        return 'Your child has a problem at school.';
    }

}