<?php

/**
 * This is the model class for table "sms".
 *
 * The followings are the available columns in table 'sms':
 * @property integer $id
 * @property integer $account
 * @property string $message
 * @property integer $hour1
 * @property integer $hour2
 * @property integer $status
 * @property integer $sent
 * @property float $charge
 * @property string $to
 * @property Account $rAccount
 */
class Sms extends CActiveRecord {
    // SMS statuses
    const STATUS_QUEUE = 0;
    const STATUS_SENDING = 1;
    const STATUS_SENT = 2;
    const STATUS_ERROR = 3;

    /**
     * Returns the static model of the specified AR class.
     * @return Sms the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sms';
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

    public function rules() {
        return array(
            array('message', 'length', 'min' => 5, 'max' => 140, 'on' => 'manualSms'),
            array('account', 'exist', 'className' => 'Account', 'attributeName' => 'id', 'allowEmpty' => false, 'on' => 'manualSms')
        );
    }
    /**
     * Adds the current message in the sending queue.
     * @param boolean $tryToSend Whether to try sending the message now (may slow down the process)
     * @return boolean Whether the action succeeded 
     */
    public function queue($tryToSend=true) {
        $now = date('G');
        $this->status = self::STATUS_QUEUE;
        if ($tryToSend===true) {
            // check if the sms should be send right now
            if ($this->hour1 >= 0 && $this->hour2 >= 0 && $this->hour1 <= $now && $now <= ($this->hour2-1)) {
                return $this->send();
            } elseif ($this->hour1 < 0 && $this->hour2 < 0) {
                return $this->send();
            }
        }
        return $this->save();
    }

    /**
     * Gets and sends all messages that should be send at the running hour in the sending queue.
     * @return int The number of sent messages
     */
    public static function sendCron() {
        $now = date('G');
        $smses = self::model()->findAll(array(
            'condition'=>'((`hour1`<=:now AND :now<=(`hour2`-1)) OR `hour1`<0) AND `status`=:st',
            'params'=>array(
                ':now'=>$now,
                ':st'=>self::STATUS_QUEUE,
            ),
        ));
        $i=0;
        foreach ($smses as $sms) {
            $sms->send();
            $i++;
        }
        return $i;
    }


    /**
     * Returneaza un text pentru oameni in functie de $status
     * @param integer $status
     * @return string The $status for humans 
     */
    public static function textStatus($status) {
        if ($type === Sms::STATUS_QUEUE)
            return 'în coada de trimitere';
        elseif ($type === Sms::STATUS_SENDING)
            return 'trimis, așteptare răspuns';
        elseif ($type === SMS::STATUS_SENT)
            return 'trimis cu succes';
        elseif ($type === Sms::STATUS_ERROR)
            return 'eroare la trimitere';
        return '';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'message' => 'Mesaj',
        );
    }

    /**
     * Sends the current SMS model to the gateway
     * @return boolean Whether the sending was a success 
     */
    public function send() {
        if (!$this->to) {
            $this->to = $this->rAccount->phone;
        }

        //sending
        $response = Yii::app()->sms->send(array('to'=>$this->to, 'message'=>$this->message));
        if (!is_array($response))
            $this->status = self::STATUS_SENDING;
        else
            $this->status = self::STATUS_ERROR;
        $this->save();
        return (bool) $response;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->getScenario() == 'manualSms') {
                $this->message = strip_tags($this->message);
            }
            return true;
        } else
            return false;
    }

}
