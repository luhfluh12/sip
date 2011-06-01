<?php

/**
 * This is the model class for table "schoolyear".
 *
 * The followings are the available columns in table 'schoolyear':
 * @property integer $id
 * @property string $start
 * @property string $change
 * @property string $end
 *
 * The followings are the available model relations:
 * @property Absences[] $absences
 */
class Schoolyear extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Schoolyear the static model class
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
		return 'schoolyear';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start, change, end', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
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
			'rBreaks' => array(self::HAS_MANY, 'Breaks', 'schoolyear'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start' => 'Începerea anului școlar',
			'change' => 'Schimbarea semestrelor',
			'end' => 'Sfârșitul anului școlar',
		);
	}
        protected function beforeSave () {
            if (parent::beforeSave()) {
                $this->start = strtotime($this->start);
                $this->change = strtotime($this->change);
                $this->end = strtotime($this->end);
                $this->id = date('Y',$this->start);
                return true;
            } else
                return false;
        }
        public static function getList() {
            $years = Schoolyear::model()->findAll(array('select'=>'start, end, id', 'order'=>'start DESC'));
            $list = array();
            foreach ($years as $year) {
                $list[$year->id]=$year->getName();
            }
            return $list;
        }

        public function getName () {
            return date('Y',$this->start).' - '.date('Y',$this->end);
        }
        /**
         * Get the school year by the beginning year
         * @param integer $start Start year, yyyy format. False for this year.
         * @param string $select Fields to be selected from DB. Defaults to ID.
         * @return The AR Model of the Schoolyear specified or NULL if not found
         */
        public function findByYear($start=false, $select='id') {
            if ($start===false) $start=date('Y');
            return $this->findByPk($start);
        }
        /**
         * Get the schoolyear $date is in.
         * @param int $date Datestamp to search in. False for time();
         * @param string $select
         * @return AR Model of the Schoolyear $date is in or NULL if not found
         */
        public function findByDate($date=false, $select='id') {
            if ($date===false) $date=time();
            return $this->findByYear(Schoolyear::yearByMonth(date('m',$date), date('Y',$date)),$select);
            /* slower way:
             return $this->find(array(
                  'condition'=>'start <= :date AND end >= :date',
                  'params'=>array(':date'=>$date),
                  'select'=>$select
             ));
             */
        }
        /**
         * Get the semester no, by date
         * @param int $date Datestamp to check
         * @param int $change Schoolyear's change property. If false,
         *  Schoolyear::findByDate() will be used
         * @return int 1 or 2. false if it fails (schoolyear does not exist)
         */
        public function getSemesterByDate($date,$change=false) {
            if ($change===false) {
               $schoolyear=$this->findByDate($date,'change');
               if ($schoolyear===null) return false;
               $change = $schoolyear->change;
            }
            return ($date <= $change ? 1 : 2);
        }
        /**
         * 
         * @param mixed $month no of month or string of format dd.mm
         * @param int $year The year in yyyy format. if false, date('Y') will be used
         * @return int The correct calendaristic year that the schoolyear 
         * should start in (yyyy format)
         */
        public static function yearByMonth($month,$year=false) {
            if ($year===false) $year=date('Y');
            if (strpos($month,'.')!==false) {
                list($day,$month) = explode('.', $month);
            }
            if ($month >= 9)
                return $year;
            else
                return $year-1;
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
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('change',$this->change,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}