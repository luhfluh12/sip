<?php

/**
 * This is the model class for table "statistics".
 *
 * The followings are the available columns in table 'statistics':
 * @property integer $id
 * @property integer $class
 * @property integer $semester
 * @property integer $year
 * @property string $key
 * @property integer $value
 * @property Classes $rClass
 */
class Statistics extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Statistics the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'statistics';
    }

    public function rules() {
        return array(
            array('class','exist','className'=>'Classes', 'attributeName'=>'id'),
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rClass' => array(self::BELONGS_TO, 'Classes', 'class'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'class' => 'Class',
            'semester' => 'Semester',
            'year' => 'Year',
            'key' => 'Key',
            'value' => 'Value',
        );
    }

    public static function statisticRules() {
        return array(
            'SAbsences',
        );
    }
    /**
     *
     * @param type $class
     * @param type $year
     * @param type $semester
     * @return type 
     */
    public static function getStatistics($class, $year, $semester) {
        $rules = self::statisticRules();
        $stats = array();
        foreach ($rules as $rule) {
            $statistic = self::model()->findByAttributes(array('class' => $class, 'year' => $year, 'semester' => $semester, 'key' => $rule));
            $thisYear = Schoolyear::thisYear(time());
            $thisSemester = Schoolyear::thisSemester(time());
            if ($statistic === null && ($thisYear>$year || ($thisYear==$year && $thisSemester>$semester) || time() > Schoolyear::model()->findByPk($year)->end)) {
                $statistic = new Statistics;
                $statistic->class = $class;
                $statistic->year = $year;
                $statistic->semester = $semester;
                $statistic->key = $rule;
                $statistic->value = $rule::calculate($class, $year, $semester);
                $statistic->save();
                $stats[$rule]=  json_decode($statistic->value, true);
            } else
                $stats[$rule]=$statistic->value;
            
            
        }
        return $stats;
    }

    protected function afterFind() {
        parent::afterFind();
        $this->value = json_decode($this->value, true);
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->value = json_encode($this->value);
            return true;
        } else
            return false;
    }

}