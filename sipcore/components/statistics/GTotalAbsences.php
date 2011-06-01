<?php
class GTotalAbsences extends GStatistics {
    protected function calculate() {
        $students = Student::getByClass($this->class);
        $query = 'SELECT COUNT(id) FROM `absences` WHERE student IN ('.implode(',',$students).')';
        $total = Yii::app()->db->createCommand()->setText($query)->queryScalar();
        $query .= ' AND authorized='.Absences::STATUS_AUTH;
        $auth = Yii::app()->db->createCommand()->setText($query)->queryScalar();
        $unauth = $total - $auth;
        return array(
            'authorized'=>$auth,
            'unauthorized'=>$unauth,
            'total'=>$total,
        );
    }
    protected function preRender() {
        $temp = $this->value;
        if (!is_array($temp))
               $temp = json_decode($temp);
        return $temp;
    }
    public function render() {
        if (parent::render()) {
            
        }        
    }
}

