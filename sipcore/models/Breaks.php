<?php

/**
 * This is the model class for table "breaks".
 *
 * The followings are the available columns in table 'breaks':
 * @property integer $id
 * @property integer $schoolyear
 * @property string $start
 * @property string $end
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Schoolyear $schoolyear0
 */
class Breaks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Breaks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'breaks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('schoolyear, start, end, name', 'required'),
			array('schoolyear', 'numerical', 'integerOnly'=>true),
                        array('schoolyear','exist','attributeName'=>'id','className'=>'Schoolyear','allowEmpty'=>false),
			array('name', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, schoolyear, start, end, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'rSchoolyear' => array(self::BELONGS_TO, 'Schoolyear', 'schoolyear'),
		);
	}

        public function checkDate($date) {
            $break = $this->find(array('select'=>'name','condition'=>'start<:date AND end>:date','params'=>array(':date'=>$date)));
            if ($break===null)
                return true;
            else
                return $break->name;
        }
        
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'schoolyear' => 'An școlar',
			'start' => 'Început vacanță',
			'end' => 'Sfârșit vacanță',
			'name' => 'Denumire',
		);
	}
        
        protected function beforeSave () {
            if (parent::beforeSave()) {
                $this->start = strtotime($this->start);
                $this->end = strtotime($this->end);
                return true;
            } else
                return false;
        }
        
        
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('schoolyear',$this->schoolyear);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}