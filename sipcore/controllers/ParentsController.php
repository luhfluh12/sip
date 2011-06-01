<?php

class ParentsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        private $_model=false;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
                    'viewMy + view',
                    'accessControl - view', // perform access control for CRUD operations
		);
	}

        public function filterViewMy($c) {
            if (Yii::app()->user->isGuest)
                throw new CHttpException(403,'Acces respins');
            if (!isset($_GET['id']))
                throw new CHttpException(404,'Pagina nu există');
            $account = Account::model()->findByPk(Yii::app()->user->id);
            if ($account===null)
                throw new CHttpException(403,'Acces respins');
            if ($account->type==Account::TYPE_PARENT && $account->info==$_GET['id']) {
                $c->run();
                return true;
            } else
                throw new CHttpException(403,'Acces respins');
            return false;
        }
        
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
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
		$this->render('view',array(
			'model'=>$this->loadModel($id,true),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Parents;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Parents']))
		{
			$model->attributes=$_POST['Parents'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Parents']))
		{
			$model->attributes=$_POST['Parents'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
		$dataProvider=new CActiveDataProvider('Parents');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Parents('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Parents']))
			$model->attributes=$_GET['Parents'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id,$students=false)
	{
            if ($this->_model === false) {
                if ($students === false)
                    $this->_model = Parents::model()->findByPk((int)$id);
                else
                    $this->_model = Parents::model()->with('rStudent')->findByPk((int)$id);
            }
            if($this->_model===null)
                throw new CHttpException(404,'Pagina cerută nu există.');
            return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='parents-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
