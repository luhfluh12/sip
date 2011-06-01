<?php

class ScheduleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/ajax';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'viewMyClass',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($weekday, $class, $hour)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($weekday, $class, $hour)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

        public function actionSaveAll (array $newHour, $class) {
 //           $class = Classes::model()->findByPk((int) $class);
   //         if ($class===null)
     //           throw new CHttpException(404, 'Clasa nu există.');
       //     $class = $class->id;

         //   $this->redirect(array('classes/view','id'=>$class));
            echo "it works";
        }
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($weekday, $class, $hour)
	{
		$model=Schedule::model()->findByPk(array('weekday'=>(int) $weekday, 'class'=>(int) $class, 'hour'=>(int) $hour));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}
