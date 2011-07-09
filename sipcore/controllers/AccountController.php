<?php

class AccountController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    private $_model = null;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'view'),
                'roles' => array('admin'),
            ),
            array('allow',
                'actions' => array('update', 'index'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('reset', 'activate'),
                'users' => array('?'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
                'message' => 'Acces respins',
            ),
        );
    }

    public function actionIndex() {
        $model = $this->loadModel(Yii::app()->user->id);
        if (isset(Yii::app()->user->homepage) && Yii::app()->user->homepage[0] !== 'account/index') {
            $this->redirect(Yii::app()->user->homepage);
        } else {
            $this->render('index', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($p='general') {
        $model = $this->loadModel(Yii::app()->user->id);

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
            if ($model->save())
                Yii::app()->user->setFlash('account_updated', 'Contul a fost actualizat cu succes.');
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Cerere invlaidă. Te rugăm să nu mai repeți această cerere.');
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Account('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Account']))
            $model->attributes = $_GET['Account'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionReset($step=1) {
        $step = (int) $step;
        if ($step === 1) {
            echo "Step 1: Enter your e-mail adress or phone no.";
        } elseif ($step === 2) {
            echo "Step 2: Enter the code you received in the e-mail/phone. (you can just click the link if you used email)";
        } elseif ($step === 3) {
            echo "Step 3: Enter your SIP-Registred child's FULL name:";
        } elseif ($step === 4) {
            echo "Step 4: Choose a new password:";
        } elseif ($step === 5) {
            echo "Congratulations. Your new password has been saved. You can now login.";
        } else {
            echo "nothing found.";
        }
    }

    /**
     * Helps user login and proceed to account setup
     * @param integer $code The activation code
     */
    public function actionActivate($code=0) {
        $this->layout = '//layouts/column1';
        if (isset($_POST['code']) && $_POST['code']) {
            $code = $_POST['code'];
        }
        if ($code !== 0) {
            $model = Account::findByActivationCode($code);
            if ($model !== null) {
                if (isset($_POST['Account'])) {
                    $model->setScenario('setupPassword');
                    $model->attributes = $_POST['Account'];
                    $model->activation='';
                    if ($model->save()) {
                        Yii::app()->user->setFlash('activate',1);
                        $this->redirect(array('site/index'));
                    }
                } 
                $this->render('activation/page', array('step' => 2, 'model' => $model, 'code'=>$code));
            } else {
                $this->render('activation/page', array('step' => 1, 'error' => 'Codul de activare este incorect. Încercați din nou.'));
            }
        } else {
            $this->render('activation/page', array('step' => 1));
        }
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id=false) {
        if ($this->_model === null) {
            if ($id !== false) {
                $this->_model = Account::model()->findByPk((int) $id);
                if ($this->_model === null)
                    throw new CHttpException(404, 'The requested page does not exist.');
            } else
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'account-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
