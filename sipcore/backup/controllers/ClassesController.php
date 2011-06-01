<?php

class ClassesController extends Controller
{
    
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/tabs';
        private $_models=array();

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl - view schedule',
                        'viewMyClass + view schedule'
		);
	}

        public function filterViewMyClass ($c) {
            if (Yii::app()->user->checkAccess('admin')) {
                $c->run();
            } else {
                $model = $this->loadModel(isset($_GET['id']) ? (int)$_GET['id'] : 0);
                // luam tipul contului si datele necesare
                $account = Account::model()->findByPk(Yii::app()->user->id);
                if ($account!==NULL && $account->type == Account::TYPE_TEACHER && $account->rTeacher->class === $model->id)
                        $c -> run();
                elseif ($account!==NULL && $account->type == Account::TYPE_SCHOOL && $model->school == $account->info)
                        $c -> run();
                else
                        throw new CHttpException(403,'You can\'t see this class.');
            }
        }

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
        
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','create','update', 'admin', 'delete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
                $class = $this->loadModel($id);
                $school = $class->rSchool;
		$this->render('view',array(
                    'class'=>$class,
                    'school'=>$school,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$class=new Classes;
                $teacher=new Teacher;
                $account=new Account;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Classes'], $_POST['Account'], $_POST['Teacher']))
		{
			$class->attributes=$_POST['Classes'];
                        $teacher->attributes=$_POST['Teacher'];
                        $account->attributes=$_POST['Account'];
                        
                        $teacher->school=$class->school;
                        $teacher->class=$class->id;
                        
                        $account->type=Account::TYPE_TEACHER;
                        $account->password=$account->randomString(12);
                        $account->info=$teacher->id;

                        $valid = $class->validate();
                        $valid = $teacher->validate() && $valid;
                        $valid = $account->validate() && $valid;

                        if ($valid) {
                            $class->save(false);

                            $teacher->class=$class->id;
                            $teacher->save(false);

                            $account->info=$teacher->id;
                            $account->save(false);

                            $this->redirect(array('view','id'=>$class->id));
                        }
		}

		$this->render('create',array(
			'class'=>$class,
                        'teacher'=>$teacher,
                        'account'=>$account,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$class=$this->loadModel($id);
                $teacher=Teacher::model()->findByAttributes(array('class'=>$class->id));
                $account=Account::model()->findByAttributes(array('info'=>$teacher->id,'type'=>Account::TYPE_TEACHER));
                $account->setScenario('updatePart');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Classes'], $_POST['Teacher'], $_POST['Account']))
		{
			$class->attributes=$_POST['Classes'];
                        $teacher->attributes=$_POST['Teacher'];
                        $account->attributes=$_POST['Account'];
                        
                        $teacher->school=$class->school;

                        $valid = $class->validate();
                        $valid = $teacher->validate() && $valid;
                        $valid = $account->validate() && $valid;

                        if ($valid) {
                            $class->save(false);
                            $teacher->save(false);
                            $account->save(false);
                            $this->redirect(array('view','id'=>$class->id));
                        }
		}

		$this->render('update',array(
                    'class'=>$class,
                    'teacher'=>$teacher,
                    'account'=>$account,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Classes');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

        public function actionSchedule($id)
        {
            $schedule = Schedule::model()->getClassSchedule((int) $id);
            $this->renderPartial('tab_schedule',array('data'=>$schedule),false,true);
        }
        
        
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Classes('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Classes']))
			$model->attributes=$_GET['Classes'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id,$redirect=true)
	{
                if (isset($this->_models[$id])) 
                        $model = $this->_models[$id];
                else {
                    $model=Classes::model()->findByPk((int)$id);
                    $this->_models[$id]=$model;
                }
		if($model===null) {
                    if ($redirect) throw new CHttpException(404,'The requested page does not exist.');
                    return false;
                }                
		return $model;
	}


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='classes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
