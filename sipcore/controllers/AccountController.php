<?php

class AccountController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
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
                'actions' => array('lostPassword', 'activate', 'lostActivationCode'),
                'users' => array('?'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
                'message' => 'Acces respins',
            ),
        );
    }

    public function actionIndex() {
        $this->layout='//layouts/column2';
        $model = $this->loadModel(Yii::app()->user->id);
        $this->render('index', array(
            'model' => $model,
        ));
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
        $this->layout = '//layouts/column2';
        if (in_array($p, array('general', 'password', 'phone', 'email', 'question')) === false)
            $p = 'general';

        $model = $this->loadModel(Yii::app()->user->id);

        if (isset($_POST['Account'])) {
            $model->setScenario('ch' . $p);
            if ($p === 'phone') {
                $stored = $model->storeOldValue($model->phone);
            } elseif ($p === 'email') {
                $stored = $model->storeOldValue($model->email);
            } elseif ($p === 'question') {
                // storing the answer hash and the old question ID in json format;
                $stored = $model->storeOldValue(json_encode(array($model->security_question => $model->security_answer)));
            } else {
                $stored = true;
            }
            if ($stored) {
                $model->attributes = $_POST['Account'];
                if ($model->save())
                    Yii::app()->user->setFlash('account_updated', 'Contul a fost actualizat cu succes.');
            } else {
                $model->addError('', 'A apărut o eroare. Vă rugăm încercați din nou.');
            }
        }

        $this->render('update', array(
            'model' => $model,
            'p' => $p,
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

    public function actionLostPassword() {
        $this->render('reset');
    }

    /**
     * Helps user resent the activation code (actually, regenerate and resend)
     */
    public function actionLostActivationCode() {
        if (isset($_POST['login'])) {
            $model = Account::findByLogin($_POST['login']);
            if ($model===null) {
                $error = 'Numărul de telefon sau adresa de e-mail introdusă nu este înregistrată în SIP.';
            } elseif (!$model->activation) {
                $error = 'Contul dvs. este deja activ. Nu vă putem retrimite codul de activare.';
            } else {
                $model->generateActivationCode();
                $model->save();
                Yii::app()->user->setFlash('resent','Un nou cod de activare a fost generat și trimis. În cazul e-mail-ului, vă rugăm să verificați folderele SPAM și BULK.');
                $this->redirect(array('account/activate'));
            }
        }
        $this->render('activation/regenerate', isset($error) ? array('error'=>$error) : array());
    }
    
    /**
     * Helps user login and proceed to account setup
     * @param integer $code The activation code
     */
    public function actionActivate($code=0) {
        if (isset($_POST['code']) && $_POST['code']) {
            $code = $_POST['code'];
        }
        if ($code !== 0) {
            $model = Account::findByActivationCode($code);
            if ($model !== null) {
                if (isset($_POST['Account'])) {
                    $model->setScenario('setupPassword');
                    $model->attributes = $_POST['Account'];
                    $model->activation = '';
                    $model->registered = time();
                    if ($model->save()) {
                        Yii::app()->user->setFlash('activate', 1);
                        $this->redirect(array('site/index'));
                    }
                }
                $this->render('activation/page', array('step' => 2, 'model' => $model, 'code' => $code));
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
     * @return Account The requested model
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
