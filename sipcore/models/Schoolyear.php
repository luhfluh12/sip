<?php

/**
 * This is the model class for table "schoolyear".
 *
 * The followings are the available columns in table 'schoolyear':
 * @property integer $id
 * @property string $start
 * @property string $change
 * @property string $end
 */
class Schoolyear extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Schoolyear the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'schoolyear';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start, change, end', 'required'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'start' => 'Începerea anului școlar',
            'change' => 'Prima zi din semestrul 2',
            'end' => 'Sfârșitul anului școlar',
        );
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->start = strtotime($this->start);
            $this->change = strtotime($this->change);
            $this->end = strtotime($this->end);
            $this->id = date('Y', $this->start);
            return true;
        } else
            return false;
    }

    /**
     * @return string The formal display of the current schoolyear. 
     */
    public function getName() {
        return date('Y', $this->start) . ' - ' . date('Y', $this->end);
    }

    /**
     * Returns the schoolyear ID that the given timestamp belongs to
     * @example Returns 2011 for the schoolyear 2011-2012
     * @param integer $time timestamp
     * @return integer The schoolyear ID
     */
    public static function thisYear($time) {
        if (date('n', $time) >= 9)
            return (int) date('Y', $time);
        return (int) date('Y', $time) - 1;
    }

    /**
     * Get the semester that the given timestamp belongs to.
     * The database is queried only if the timestamp is in February
     * @param integer $time The timestamp
     * @return integer The semester (1 or 2). FALSE if the function fails
     */
    public static function thisSemester($time) {
        $month = (int) date('n', $time);
        if ($month > 2) {
            return 2;
        } elseif ($month < 2) {
            return 1;
        }
        $model = self::model()->findByPk(self::thisYear($time));
        if ($model === null)
            return false;
        if ($time >= $model->change)
            return 2;
        return 1;
    }

    public static function isYearActive($year=false) {
        if ($year === false)
            $year = Schoolyear::thisYear(time());
        $model = self::model()->findByPk($year);
        if ($model === null)
            return false;
        return $model->end < time();
    }

}