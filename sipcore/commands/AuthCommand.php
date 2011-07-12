<?php

class AuthCommand extends CConsoleCommand {

    /**
     * Outputs a row with the given authorization
     * Doesn't display the account, just account ID
     * @param Authorization $auth 
     */
    private function renderAuth($auth) {
        echo '#', $auth->id, '  ',
        '(account ID: ', $auth->account, ')  ',
        $auth->action, ':',
        $auth->value, "\n";
    }

    private function getAccount($login) {
        if (mb_strpos($login, '@'))
            $account = Account::model()->with('rAuthorizations')->find(array(
                        'condition' => 'email=:email',
                        'select' => 'id',
                        'params' => array(':email' => $login),
                    ));
        else
            $account = Account::model()->with('rAuthorizations')->find(array(
                        'condition' => 'phone=:phone',
                        'select' => 'id',
                        'params' => array(':phone' => $login),
                    ));
        return $account;
    }

    public function actionShow($login) {
        $account = $this->getAccount($login);
        if ($account === null) {
            Yii::app()->end("Account not found.\n");
        }

        foreach ($account->rAuthorizations as $auth) {
            $this->renderAuth($auth);
        }
        echo "Done.\n";
    }

    public function actionAdd($login, $action, $value) {
        $account = $this->getAccount($login);
        if ($account === null) {
            Yii::app()->end("Account not found.\n");
        }

        $auth = new Authorization();
        $auth->account = $account->id;
        $auth->action = $action;
        $auth->value = $value;
        if ($auth->save()) {
            echo "Successfully added.\n";
        } else {
            echo "A apărut o eroare:\n";
            foreach ($auth->getErrors() as $arr)
                echo $arr[0] . "\n";
            echo "\n";
        }
    }

    public function actionDrop($login) {
        $account = $this->getAccount($login);
        if ($account === null) {
            Yii::app()->end("Account not found.\n");
        }
        $query = "DELETE FROM " . Authorization::model()->tableName() . " WHERE account=:account";
        $command = Yii::app()->db->createCommand($query);
        $command->bindValue(':account', $account->id, PDO::PARAM_INT);
        echo "Au fost șterse ", $command->execute(), " permisiuni.\n";
    }

    public function actionRemove($login, $action, $value, $all=false) {
        $account = $this->getAccount($login);
        if ($account === null) {
            Yii::app()->end("Account not found.\n");
        }
        if ($all !== false) {
            $query = "DELETE FROM " . Authorization::model()->tableName() . " WHERE
                action=:action AND account=:account";
            $command = Yii::app()->db->createCommand($query);
            $command->bindValue(':action', $action, PDO::PARAM_STR);
            $command->bindValue(':account', $account->id, PDO::PARAM_INT);
            echo "Au fost șterse ", $command->execute(), " permisiuni.\n";
        } else {
            $query = "DELETE FROM " . Authorization::model()->tableName() . " WHERE
                action=:action AND account=:account AND value=:value";
            $command = Yii::app()->db->createCommand($query);
            $command->bindValue(':action', $action, PDO::PARAM_STR);
            $command->bindValue(':account', $account->id, PDO::PARAM_INT);
            $command->bindValue(':value', $value, PDO::PARAM_INT);
            echo "Au fost șterse ", $command->execute(), " permisiuni.\n";
        }
    }

    public function actionKill($id) {
        $auth = Authorization::model()->findByPk($id);
        if ($auth === null) {
            Yii::app()->end("Invalid authorization ID.\n");
        }
        if ($auth->delete()) {
            echo "Successfully deleted. \n";
        } else {
            echo "Unexpected error. Try again. \n";
        }
    }

}