<?php

class SchoolController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('create','update','admin','delete'),
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
                if (!Yii::app()->user->checkAccess('admin'))
                        $this->layout = 'column1';
                $model=$this->loadModel($id);
		$this->render('view',array(
			'model'=>$model,
                        'class'=>$model->rClasses,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$school=new School;
                $account=new Account;
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['School'], $_POST['Account']))
		{
			$school->attributes=$_POST['School'];
                        // checks if the phone no already exists in db
                        
                        
                        $account->attributes=$_POST['Account'];
                        
                        $account->password=$account->randomString(12);
                        $account->type=Account::TYPE_SCHOOL;
                        
                        $valid = $school->validate();
                        $valid = $account->validate() && $valid;
                        
			if($valid) {
                            $school->save(false);
                            $account->info=$school->id;
                            $account->save(false);
                            
                            $this->redirect(array('view','id'=>$school->id));
                        }
		}

		$this->render('create',array(
                    'school'=>$school,
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
		$school=$this->loadModel($id);
                $account=Account::model()->findByAttributes(array('type'=>Account::TYPE_SCHOOL,'info'=>$school->id));
                $account->setScenario('updatePart');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['School'], $_POST['Account']))
		{
			$school->attributes=$_POST['School'];
                        $account->attributes=$_POST['Account'];

                        $valid = $school->validate();
                        $valid = $account->validate() && $valid;

			if($valid) {
                            $school->save(false);
                            $account->save(false);
                            
                            $this->redirect(array('view','id'=>$school->id));
                        }
		}

		$this->render('update',array(
                    'school'=>$school,
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
                if (!Yii::app()->user->checkAccess('admin'))
                        $this->layout='column1';
		$dataProvider=new CActiveDataProvider('School');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new School('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['School']))
			$model->attributes=$_GET['School'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=School::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='school-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
