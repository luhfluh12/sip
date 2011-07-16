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
            if (Yii::app()->user->checkAccess('formteacher:'.$student->class)) {
                $this->_adminOptions = true;
                $c->run();
                return true;
            } elseif (Yii::app()->user->checkAccess('parent:'.$student->id)) {
                $c->run();
                return true;
            }
        }
        throw new CHttpException(403,'Acces respins.');
        return false;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->layout = '//layouts/column1';
        $student = Student::model()->with('rSchool', 'rClass')->findByPk((int) $id);
        $subjects = $student->rClass->rSubjects;
        $this->render('view', array(
            'student' => $student,
            'subjects' => $subjects,
            'adminOptions' => $this->_adminOptions,
        ));
    }

    public function actionStats($id) {
        $this->layout = '//layouts/column1';
        $student = Student::model()->with('rSchool', 'rClass', 'rAverages.rSubject')->findByPk((int) $id);
        $this->render('stats', array(
            'student' => $student,
        ));
    }

    public function actionSms($id) {
        $id = (int) $id;
        $this->layout = '//layouts/column1';
        $student = Student::model()->with('rSchool', 'rClass', 'rSmses')->findByPk($id);
        if ($student === null)
            throw new CHttpException(404, 'Elevul nu există');
        if (isset($_POST['Sms'])) {
            $model = new Sms('manualSms');
            $model->attributes = $_POST['Sms'];
            if ($model->validate()) {
                $model->student = $id;
                $model->added = time();
                $model->status = Sms::STATUS_TOSEND;
                $model->save();
                $this->redirect(array('student/sms', 'id' => $id, 'sent' => 1));
            }
        }
        /* var_dump($student->rSmses);
          var_dump($student); */
        $this->render('sms', array(
            'student' => $student,
        ));
    }

    /* public function actionManualSmsForm($id)
      {
      $id = (int) $id;
      $model=new Sms('manualSms');
      $student = Student::model()->with('rParent.rAccount')->findByPk($id);
      if ($student !== null)
      if(isset($_POST['Sms']))
      {
      $model->attributes=$_POST['Sms'];
      if($model->validate())
      {
      $model->student=$this->id;
      $model->added = time();
      $model->status = Sms::STATUS_TOSEND;
      $model->save();
      $this->redirect(array('student/sms','id'=>$id,'sent'=>1));
      }
      }
      $this->redirect(array('student/sms','id'=>$id,'sent'=>0));
      } */

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
                        Yii::app()->user->setFlash('addstudent_success','Elevul a fost adăugat cu succes.');
                        $this->redirect(array('create', 'class' => $class->id));
                    }
                }
            } else {
                $account = new Account;
                $account->attributes=$_POST['Account'];
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
        $parent = $student->rParent;
        $pAccount = Account::model()->find('info=:info AND type=:type', array(':info' => $parent->id, ':type' => Account::TYPE_PARENT));
        // $sAccount = Account::model()->find('info=:info AND type=:type',
        //       array(':info'=>$student->id,':type'=>Account::TYPE_STUDENT));
        $student->setScenario('updatePart');
        $parent->setScenario('updatePart');
        $pAccount->setScenario('updatePart');
        //$sAccount->setScenario('updatePart');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Student'], $_POST['Parents'], /* $_POST['sAccount'], */ $_POST['pAccount'])) {
            $student->attributes = $_POST['Student'];
            $parent->attributes = $_POST['Parents'];
            //  $sAccount->attributes=$_POST['sAccount'];
            $pAccount->attributes = $_POST['pAccount'];

            $valid = $student->validate();
            $valid = $parent->validate() && $valid;
            //$valid = $sAccount->validate() && $valid;
            $valid = $pAccount->validate() && $valid;

            if ($valid) {

                $saved = $parent->save();
                $pAccount->info = $parent->id;
                $saved = $pAccount->save() && $saved;

                $student->parent = $parent->id;
                $saved = $student->save() && $saved;

                //$sAccount->info=$student->id;
                //$saved=$sAccount->save() && $saved;
                if (!$saved) {
                    $parent->delete();
                    $pAccount->delete();
                    //  $sAccount->delete();
                    $student->delete();
                }
                $this->redirect(array('view', 'id' => $student->id));
            }
        }

        $this->render('update', array(
            'student' => $student,
            'parent' => $parent,
            //'sAccount'=>$sAccount,
            'pAccount' => $pAccount,
            'classes' => $student->rClass,
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
