<?php

/**
 * This is the model class for table "subjects".
 *
 * The followings are the available columns in table 'subjects':
 * @property integer $id
 * @property string $name
 */
class Subject extends CActiveRecord
{
        const ID_PURTARE=29;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Subject the static model class
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
		return 'subjects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
                    'rClasses'=>array(self::MANY_MANY,'Classes','schedule(subject, class)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
		);
	}
        
        public static function getSubjectName($id) {
            $model = Subject::model()->findByPk((int)$id, array('select'=>'name'));
            if ($model!==null)
                return $model->name;
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
            $criteria->compare('name',$this->name,true);

            return new CActiveDataProvider(get_class($this), array(
                    'criteria'=>$criteria,
            ));
	}
        
        /**
         * If the subject given does not exist, create it.
         * If a class is defined, also checks if assiged and if not,
         * assigns the class with the subject
         * @param string $subject The subject name. It has ucwords applied
         * [@param int $class The class ID where the subject will be assigned]
         * @return The id of the subject or false if it fails.
         */
        public function getSubjectId($subject, $class=false) {
            // standardize subjects
            $subject = ucwords(strtolower($subject));

            // check if the subject already exists
            $subjectModel = $this->find(
                    array('select'=>'id',
                        'condition'=>'name=:subj',
                        'params'=>array(':subj'=>$subject),
                        ));

            // if the subject doesn't exist, create it
            if ($subjectModel===null) {
                $subjectModel = new Subject;
                $subjectModel->name = $subject;
                if (!$subjectModel->save())
                    return false;
            }

            // checks if the subject is assigned to $class
            if ($class!==false) {
                $this->checkAssign($class,$subjectModel->id,'add');
            }
            return $subjectModel->id;
        }
        /**
         *  Checks if a class has a subject.
         * @param $class integer
         * @param $subject integer
         * @param $do=false mixed Value 'add' adds the assign automatically if needed
         *  'remove' deletes the assign if exists
         * @return bool For $do===false it returns the existence of the assignment,
         *  otherwise, if insert or delete succeeded
         */
        public function checkAssign($class, $subject, $do=false) {
            $query = "SELECT 1 FROM {{classes_subjects_assign}}
                WHERE class=:class AND subject=:subject";
            $command = Yii::app()->db->createCommand($query);
            $fail=false;
            $exists = $command->queryRow(true,array(':class'=>$class,'subject'=>$subject)) !== false;
            if ($exists===false && $do==='add'){
                $insert = Yii::app()->db->createCommand()
                    ->insert('{{classes_subjects_assign}}',array(
                        'class'=>$class,
                        'subject'=>$subject));
                $fail = $insert !== 0;
            } elseif ($exists===true && $do==='remove') {
                $remove = Yii::app()->db->createCommand()->
                        delete('{{classes_subjects_assign}}',
                          'class=:class AND subject=:subject',
                          array(':class'=>$class,':subject'=>$subject));
                $fail = $remove !== 0;
            }
            return ($do===false ? $exists : $fail);

        }
        
        /**
         * Generates the list of suggestions for CJuiAutoComplete
         * @param string $term
         * @return string CJSON encoded list of suggested subjects 
         */
        public function autoComplete ($term) {
            $query ="SELECT name FROM ".$this->tableName()." WHERE name LIKE :term LIMIT 0,5";
            $command =Yii::app()->db->createCommand($query);
            $command->bindValue(":term", '%'.$term.'%', PDO::PARAM_STR);
            return CJSON::encode($command->queryColumn());
        }
}