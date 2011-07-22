<?php

class StudentController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    // caching the model
    private $_model = false;
    private $_adminOptions = false;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'manageStudents + delete, update, create',
            'viewStudentStats + view, stats, sms',
            'accessControl - view, stats, sms, delete, create, update',
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
                'actions' => array('admin', 'index'),
                'roles' => array('admin'),
            ),
            array('allow',
                'actions' => array('ajaxSearch'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function filterManageStudents($c) {
        if (Yii::app()->user->checkAccess('admin') ||
                (isset($_GET['class']) && Yii::app()->user->checkAccess('formteacher:' . intval($_GET['class'])))) {
            $c->run();
            return true;
            // for non-Create actions
        } elseif (isset($_GET['id'])) {
            $student = $this->loadModel($_GET['id']);
            if (Yii::app()->user->checkAccess('formteacher:' . $student->class)) {
                $c->run();
                return true;
            }
        }
        throw new CHttpException(403, 'Acces respins.');
        return false;
    }

    public function filterViewStudentStats($c) {
        if (Yii::app()->user->checkAccess('admin')) {
            $this->_adminOptions = true;
            $c->run();
            return true;
        } elseif (isset($_GET['id'])) {
            $student = $this->loadModel($_GET['id']);
            if (Yii::app()->user->checkAccess('formteacher:' . $student->class)) {
                $this->_adminOptions = true;
                $c->run();
                return true;
            } elseif (Yii::app()->user->checkAccess('parent:' . $student->id)) {
                $c->run();
                return true;
            }
        }
        throw new CHttpException(403, 'Acces respins.');
        return false;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->layout = '//layouts/column1';
        $student = Student::model()->with('rSchool', 'rClass.rSubjects')->findByPk((int) $id);
        $subjects = $student->rClass->rSubjects;
        $purtare = Average::getPurtare($student->id);
        $this->render('view', array(
            'student' => $student,
            'subjects' => $subjects,
            'purtare' => $purtare,
            'adminOptions' => $this->_adminOptions,
        ));
    }

    public function actionStats($id) {
        $this->layout = '//layouts/column1';
        $student = Student::model()->with('rSchool', 'rClass', 'rChart.rSubject')->findByPk((int) $id);

        // generate the json for the chart
        /** @todo clean this up */
        $comma = false;
        $comma2 = false;
        $json = '{';
        $subject = false;
        foreach ($student->rChart as $point) {
            if ($point->subject != $subject) {
                if ($subject) {
                    //$json .= "]}";
                }
                $json .= ( $comma ? "]},\n" : "") . '"m' . $point->subject . "\":{\n" .
                        "label:\"" . $point->rSubject->name . "\",\n" .
                        "data:[";
                if ($comma === false)
                    $comma = true;
                $comma2 = false;
                $subject = $point->subject;
            }
            $json .= ( $comma2 ? ',' : '') . "[" . $point->date . "000" . ", " . $point->average . "]";
            if ($comma2 === false)
                $comma2 = true;
        }
        $json .= ']}}';

        $this->render('stats', array(
            'student' => $student,
            'json' => $json,
        ));
    }

    public function actionSms($id) {
        $id = (int) $id;
        $this->layout = '//layouts/column1';
        $student = Student::model()->with('rParent.rSmses')->findByPk($id);
        if ($student === null)
            throw new CHttpException(404, 'Elevul nu există');
        if (isset($_POST['Sms']['message'])) {
            $sms = new Sms('manualSms');
            $sms->message = $_POST['Sms']['message'];
            $sms->account = $student->parent;
            $sms->hour1 = $student->rParent->sms_hour1;
            $sms->hour2 = $student->rParent->sms_hour2;
            $sms->queue(true);
            Yii::app()->user->setFlash('student_sms_queued', 'Mesajul SMS va fi trimis în următoarele 24 de ore.');
            $this->redirect(array('student/sms', 'id' => $id));
        }

        $this->render('sms', array(
            'student' => $student,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Student');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($class) {
        $class = Classes::model()->findByPk($class);
        if ($class === null)
            throw new CHttpException(404, 'Clasa unde vrei să adaugi un elev nu există.');

        $student = new Student;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Student'], $_POST['Account'], $_POST['Account']['phone'])) {
            $student->attributes = $_POST['Student'];
            if ($student->validate()) {
                // do account "magic"
                $account = Account::model()->findByLogin($_POST['Account']['phone']);
                if ($account === null) {
                    $account = new Account;
                    $account->attributes = $_POST['Account'];
                    $error = !$account->save();
                } else {
                    $error = false;
                }
                if ($error === false) {
                    $student->parent = $account->id;
                    $student->class = $class->id;
                    $student->school = $class->school;
                    if ($student->save(false)) {
                        $auth = new Authorization;
                        $auth->give($account->id, 'parent', $student->id);
                        Yii::app()->user->setFlash('addstudent_success', 'Elevul a fost adăugat cu succes.');
                        $this->redirect(array('create', 'class' => $class->id));
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
            'student' => $student,
            'account' => $account,
            'class' => $class,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $student = $this->loadModel($id);
        if (isset($_POST['Student'])) {
            $student->attributes = $_POST['Student'];
            if ($student->save()) {
                $this->redirect(array('view', 'id' => $student->id));
            }
        }
        $this->render('update', array(
            'student' => $student,
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
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Student('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Student']))
            $model->attributes = $_GET['Student'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        if ($this->_model === false)
            $this->_model = Student::model()->findByPk((int) $id);

        if ($this->_model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

}
