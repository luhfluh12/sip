<?php

/**
 * This is the model class for table "accountRevisions".
 *
 * The followings are the available columns in table 'accountRevisions':
 * @property integer $id Revision ID
 * @property integer $account Account ID
 * @property string $action Action of the revision
 * @property string $oldvalue The old value that was changed
 * @property string $useragent The useragent used
 * @property string $ip The IP of the request
 * @property string $timestamp The time when the change was done
 * @property Account $rAccount Relative Account model
 */
class AccountRevision extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param #D__CLASS__|? $className
     * @return AccountRevision the static model class
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
		return 'accountRevisions';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'rAccount' => array(self::BELONGS_TO, 'Account', 'account'),
		);
	}
        
    protected function beforeSave () {
        if ($this->isNewRecord) {
            $this->ip = Yii::app()->request->getUserHostAddress();
            $this->useragent = Yii::app()->request->getUserAgent();
            $this->timestamp=time();
        }
        return true;
    }
}