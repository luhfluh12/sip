<?php

/**
 * This is the model class for table "schools".
 *
 * The followings are the available columns in table 'schools':
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property integer $account
 * @property array $rClasses
 * @property Account $rAccount
 */
class School extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return School the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'schools';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, city', 'required'),
            array('name', 'length', 'max' => 100),
            array('city', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, city', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rClasses' => array(self::HAS_MANY, 'Classes', 'school', 'order' => 'grade ASC, name ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Denumire școală',
            'city' => 'Oraș',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        $criteria->compare('city', $this->city, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }
}