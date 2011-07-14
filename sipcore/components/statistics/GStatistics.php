<?php
/**
 * @param integer $class
 * @param integer $schoolyaer
 * @param integer $semester
 * @param mixed $value
 * @param string $_display
 */

class GStatistics {
    public $class=false;
    public $schoolyear=false;
    public $semester=false;
    public $value=false;
    private $_display=false;
    
    /**
     * Initializes the statistic generator
     * @param array $options
     */
    
    public function __construct($options) {
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                if (property_exists($this, $option)) {
                    $this->$option = $value;
                }
            }
        }
    }
    
    public function render() {
        if ($this->_display===false) {
            if ($this->value===false)
                    $this->value=$this->calculate ();
            $this->_display = $this->preRender();
        }
        return $this->_display;
    }

    protected function preRender() {
        return $this->value;
    }
    
    protected function calculate() {
        return 1;
    }
    
    public function save () {
        return (is_array($this->value) ? json_encode($this->value) : $this->value);
    }
}

?>