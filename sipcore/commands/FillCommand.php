<?php

class AuthCommand extends CConsoleCommand {

    protected $_names=null;
    protected $_namesCount = 0;
    protected function getValidDatesBySubject($class) {
        
    }

    public function actionMarks() {
        
    }

    public function actionAbsences() {
        
    }
    
    public function actionSchools($no) {
        $school = new School;
        if (mt_rand(0,1)===0)
            $school->name = 'Liceul Teoretic "';
        else
            $school->name = 'Liceul Pedagogic "';
        $school->name.=$this->randomName().'"';
        $school->city = mt_rand(0, 5) < 3 ? 'Caransebeș' : 'Reșița';
        $school->save();
        
    }

    public function actionClasses($no) {
        
    }
    
    public function actionStudents($no) {
        
    }

    /**
     * Returns a random good-looking name for a student.
     * @return string The name
     */
    protected function randomName() {
        if ($this->_names !== null) {
            $this->_names = include "randomNames.php";
            $this->_namesCount = count($this->_names) - 1;
        }
        return $this->_names[mt_rand(0,$this->_namesCount)];
    }
}
