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
    public static function check($student, $subject, $timelimit, $stored) {
        if (isset($stored[$subject]))
            return false;

        $lastAverage = Chart::model()->find(array(
            'condition' => 'added>=:timelimit AND average<:corig AND subject=:subj AND student=:student',
            'params' => array(
                ':timelimit' => $timelimit,
                ':student' => $student,
                ':subj' => $subject,
                ':corig' => self::CORIGENTA,
            ),
            'select' => 'average',
            'order' => 'added DESC, date DESC',
                ));
        if ($lastAverage === null)
            return false;

        $sentAverage = Chart::model()->find(array(
            'condition' => 'added<:timelimit AND average<:corig AND subject=:subj AND student=:student',
            'params' => array(
                ':timelimit' => $timelimit,
                ':student' => $student,
                ':subj' => $subject,
                ':corig' => self::CORIGENTA,
            ),
            'select' => 'average',
            'order' => 'added DESC, date DESC',
                ));
        if ($sentAverage !== null)
            return false;
        if (is_array($stored))
            return $stored+array($subject=>$lastAverage->average);
        return array($subject=>$lastAverage->average);
    }

    /**
     * The sotred data
     * @param array $stored
     * @return mixed Updated array to be stored and rendered or an empty value if there are problems
     */
    public static function validate($student, $timelimit, $stored) {
        if (!is_array($stored))
            return false;
        $new = array();
        foreach ($stored as $subject => $average) {
           $new += self::check($student, $subject, $timelimit, false);
        }
        if (!empty($new))
            return $new;
        return false;
    }

    /**
     * Render the problem to be human readable.
     * @param array $stored
     * @return string The problem, renderd properly.
     */
    public static function render($stored) {
        $subjects = '';
        foreach ($stored as $subj => $avg) {
            $subjects .= ', '.Subject::getSubjectName($subj).' ('.$avg.')';
        }
        $subjects = trim($subjects, ', ');
        return 'Corigențe la '.$subjects;
    }

}
