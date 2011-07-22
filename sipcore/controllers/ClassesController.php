<?php

class ClassesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    private $_models = array();

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl - view schedule statistics sms updatePart',
            'formTeacher + view schedule statistics sms updatePart'
        );
    }

    public function filterFormTeacher($c) {
        if (!isset($_GET['id'])) {
            throw new CHttpException(404, "Pagina nu exită");
            return false;
        }
        $_GET['id'] = (int) $_GET['id'];

        if (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('formteacher:' . $_GET['id'])) {
            $c->run();
            return true;
        }
        throw new CHttpException(403, 'Nu poți vedea acastă clasă.');
        return false;
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'roles' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->layout = '//layouts/tabs';
        $class = $this->loadModel($id);
        $school = $class->rSchool;
        $this->render('view', array(
            'class' => $class,
            'school' => $school,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $class = new Classes;
        if (isset($_POST['Classes'], $_POST['Account'], $_POST['Account']['phone'])) {
            $class->attributes = $_POST['Classes'];
            if ($class->validate()) {
                // do the account stuff and give the pemissions
                $account = Account::model()->findByLogin($_POST['Account']['phone']);
                if ($account === null) {
                    $account = new Account;
                    $account->attributes = $_POST['Account'];
                    $error = !$account->save();
                } else {
                    $error = false;
                }
                if ($error === false) {
                    $class->teacher = $account->id;
                    if ($class->save(false)) {
                        $auth = new Authorization;
                        $auth->give($account->id, 'formteacher', $class->id);
                        $this->redirect(array('view', 'id' => $class->id));
                    }
                }
            } else {
                $account = new Account;
                $account->attributes = $_POST['Account'];
                $account->validate();
            }
        } else
            $account = new Account;

        $this->render('create', array(
            'class' => $class,
            'account' => $account,
        ));
    }

    /**
     * Updates a particular model, using "partUpdate" scenario.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdatePart($id) {
        $class = $this->loadModel($id);
        $class->setScenario('updatePart');

        if (isset($_POST['Classes'])) {
            $class->attributes = $_POST['Classes'];
            if ($class->save()) {
                Yii::app()->user->setFlash('ClassesUpdate', 'Clasa a fost actualizată cu succes.');
                $this->redirect(array('view', 'id' => $class->id));
            }
        }

        $this->render('updatePart', array(
            'class' => $class,
        ));
    }

    public function actionUpdate($id) {
        $class = $this->loadModel($id);

        if (isset($_POST['Classes'])) {
            $class->attributes = $_POST['Classes'];
            if ($class->save()) {
                Yii::app()->user->setFlash('ClassesUpdate', 'Clasa a fost actualizată cu succes.');
                $this->redirect(array('view', 'id' => $class->id));
            }
        }

        $this->render('update', array(
            'class' => $class,
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
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Classes');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionSchedule($id) {
        $schedule = Schedule::model()->getClassSchedule((int) $id);
        $this->renderPartial('tab_schedule', array('data' => $schedule), false, true);
        Yii::app()->end();
    }

    public function actionStatistics($id) {
        $id = (int) $id;
        $year = Schoolyear::thisYear(time());
        $statistics1 = Statistics::getStatistics($id, $year, 1);
        $statistics2 = Statistics::getStatistics($id, $year, 2);
        echo "<h1>Semestrul 1</h1>";

        if (!empty($statistics1)) {
            foreach ($statistics1 as $key => $stored) {
                $this->renderPartial('//statistics/' . $key, array('stored' => $stored));
            }
        } else
            echo "<div>Rapoartele pe semestrul 1 nu sunt disponibile.</div>";
        if (!empty($statistics2)) {
            echo "<h1>Semestrul 2</h1>";
            foreach ($statistics2 as $key => $stored) {
                $this->renderPartial('//statistics/' . $key, array('stored' => $stored));
            }
        } else
            echo "<div>Rapoartele pe semestrul 2 nu sunt disponibile.</div>";

        Yii::app()->end();
    }

    public function actionSms($id) {
        if (isset($_POST['Sms']['message'])) {
            $id = (int) $id;
            $class = Classes::model()->with('rStudents.rParent')->findByPk($id);
            $sentTo = array();
            foreach ($class->rStudents as $student) {
                if (!in_array($student->parent, $sentTo)) {
                    $sms = new Sms('manualSms');
                    $sms->message = $_POST['Sms']['message'];
                    $sms->account = $student->parent;
                    $sms->hour1 = $student->rParent->sms_hour1;
                    $sms->hour2 = $student->rParent->sms_hour2;
                    $sms->queue(false);
                    $sentTo[]=$student->parent;
                }
            }
            Yii::app()->user->setFlash('class_sms_queued', 'Mesajul SMS va fi trimis tuturor părinților între orele alese de aceștia.');
            $this->redirect(array('classes/view', 'id' => $id));
        }
        $this->renderPartial('_smsform');
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Classes('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Classes']))
            $model->attributes = $_GET['Classes'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $redirect=true) {
        if (isset($this->_models[$id]))
            $model = $this->_models[$id];
        else {
            $model = Classes::model()->findByPk((int) $id);
            $this->_models[$id] = $model;
        }
        if ($model === null) {
            if ($redirect)
                throw new CHttpException(404, 'The requested page does not exist.');
            return false;
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'classes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
