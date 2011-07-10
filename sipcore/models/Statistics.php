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
 *
 * The followings are the available model relations:
 * @property Classes $rClass
 */
class Statistics extends StatisticsCalc
{
    
        const TYPE_NORMAL=0;
        const TYPE_JSON = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Statistics the static model class
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
		return 'statistics';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'rClass' => array(self::BELONGS_TO, 'Classes', 'class'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'class' => 'Class',
			'semester' => 'Semester',
			'year' => 'Year',
			'key' => 'Key',
			'value' => 'Value',
		);
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
		$criteria->compare('class',$this->class);
		$criteria->compare('semester',$this->semester);
		$criteria->compare('year',$this->year);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('value',$this->value);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
        
        public function getStatisticRules () {
            return array(
                'totalAbsences'=>array('text'=>'Număr total de absențe','type'=>self::TYPE_NORMAL),
                'totalAuthAbsences'=>array('text'=>'Număr total de absențe motivate','type'=>self::TYPE_NORMAL),
                'totalUnauthAbsences'=>array('text'=>'Număr total de absențe','type'=>self::TYPE_NORMAL),
            );
        }
        
        public function getStatistics($class,$recalculate=false,$schoolyear=false,$semester=false) {
            $rules = $this->getStatisticRules();
            $statistics = array();
            foreach ($rules as $stat => $info) {
                $statistics[$stat] = array(
                    'value'=>$this->getStatistic($class, $stat, $recalculate, $schoolyear, $semester),
                    'text'=>($info['type'] == self::TYPE_NORMAL ? $info['text'] : json_decode($info['text'])),
                    'type'=>$info['type'],
                );
            }
            return $statistics;
        }
        
        public function getStatistic($class, $statistic, $recalculate=false, $schoolyear=false, $semester=false) {
            $rules = $this->getStatisticRules();
            if (!isset($rules[$statistic]))
                return false;
            if ($schoolyear===false) {
                $schoolyear_model = Schoolyear::model()->findByDate();
                $semester = Schoolyear::model()->getSemesterByDate(time(),$schoolyear_model->change);
                $schoolyear=$schoolyear_model->id;
            }
            if ($semester===false) {
                $semester = Schoolyear::model()->getSemesterByDate(time());
            }
            
            $stat=$this->findByAttributes(array(
                'class'=>$class,
                'year'=>$schoolyear,
                'semester'=>$semester,
                'key'=>$statistic,
            ));
            
            if ($recalculate && $stat!==null)
                $stat = $this->calculateStatistic($class,$statistic,$schoolyear,$semester,$stat);
            elseif ($stat===null)
                $stat = $this->calculateStatistic($class, $statistic, $schoolyear, $semester);

            return $stat->value;
        }
        protected function calculateStatistic($class, $statistic, $schoolyear, $semester, $stat=false) {
            $rules = $this->getStatisticRules();
            $newValue = $this->calculate($statistic,$class,$schoolyear,$semester);
            if ($stat===false)
                $stat = new Statistics;
            $stat->value = $newValue;
            $stat->key=$statistic;
            $stat->class=$class;
            $stat->semester=$semester;
            $stat->year=$schoolyear;
            if ($stat->save())
                return $stat;
            return false;
        }
}