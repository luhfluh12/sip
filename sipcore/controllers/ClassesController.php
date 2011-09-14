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
            'accessControl - view schedule statistics sms updatePart create',
            'formTeacher + view schedule statistics sms updatePart',
            'schoolManager + create',
        );
    }

    public function filterFormTeacher($c) {
        if (!isset($_GET['id'])) {
            throw new CHttpException(404, "Pagina nu exită.");
            return false;
        }
        $_GET['id'] = (int) $_GET['id'];

        if (Yii::app()->user->checkAccess('admin') ||
                Yii::app()->user->checkAccess('formteacher:' . $_GET['id']) ||
                Yii::app()->user->checkAccess('schoolmanager:' . $this->loadModel($_GET['id'])->school)) {
            $c->run();
            return true;
        }
        throw new CHttpException(403, 'Ne pare rău, dar nu aveți acces la această clasă.');
        return false;
    }

    public function filterSchoolManager($c) {
        if (!isset($_GET['school'])) {
            throw new CHttpException(404, "Pagina nu există.");
            return false;
        }
        if (Yii::app()->user->checkAccess('schoolmanager:' . intval($_GET['school'])) || Yii::app()->user->checkAccess('admin')) {
            $c->run();
            return true;
        }
        throw new CHttpException(403, 'Ne pare rău, dar nu aveți acces la acestă pagină.');
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
    public function actionCreate($school) {
        $this->layout = '//layouts/column1';

        $school = School::model()->findByPk($school);
        if ($school === null)
            throw new CHttpException(404, "Școala nu există în baza noastră de date.");

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
                    $class->school = $school->id;
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
            'school' => $school,
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
                    $sentTo[] = $student->parent;
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
     * Find the model with findByPk() by the given $id.
     * Returns FALSE or throws a CHttpException if the model requested does not exist.
     * @param integer $id The PK of the requested model, usually $_GET['ID']
     * @param boolean $exception Whether to throw an exception or return false if the model id does not exist. Default is true.
     * @return Classes The requested model of FALSE if it doesn't exist and redirect is false. 
     */
    public function loadModel($id, $exception=true) {
        if (isset($this->_models[$id]))
            $model = $this->_models[$id];
        else {
            $model = Classes::model()->findByPk((int) $id);
            $this->_models[$id] = $model;
        }
        if ($model === null) {
            if ($exception)
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
