<?php

/**
 * This is the model class for table "authorizations".
 *
 * The followings are the available columns in table 'authorizations':
 * @property integer $id
 * @property integer $account
 * @property string $action
 * @property integer $value
 * @property Account $rAccount
 */
class Authorization extends CActiveRecord {
    const authAllValues = 0;

    /**
     * Returns the static model of the specified AR class.
     * @return Authorization the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'authorizations';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('account, action, value', 'required'),
            array('account', 'numerical', 'integerOnly' => true),
            array('account', 'exist', 'className' => 'Account', 'attributeName' => 'id'),
            array('action', 'length', 'max' => 20),
            array('value', 'length', 'max' => 8),
        );
    }
    public function give($account, $action, $value) {
        if ($this->authExists($account, $action, $value))
            return true;
        $this->attributes=array('account'=>$account,'action'=>$action,'value'=>$value);
        return $this->save();
    }
    /**
     * Checks if the specified user can do the specified authentication item
     * @param type $account
     * @param type $action
     * @param type $value
     * @return type 
     */
    public function authExists($account, $action, $value) {
        return $this->exists('account=:acc AND action=:act AND (value=:val OR value=:c)', array(
            ':acc' => $account,
            ':act' => $action,
            ':val' => $value,
            ':c' => self::authAllValues,
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rAccount' => array(self::BELONGS_TO, 'Account', 'account'),
        );
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord && $this->authExists($this->account, $this->action, $this->value)) {
                $this->addError('','The specified Account already has the action.');
                return false;
            }
            return true;
        } else
            return false;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'account' => 'Account',
            'action' => 'Action',
            'value' => 'Only at this one',
        );
    }

}