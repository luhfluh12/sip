<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
           if (Yii::app()->user->isGuest) {
		$model=new LoginForm;
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
            } else
                $this->render('index');
            
        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
                            $message = new YiiMailMessage;
                            $message->view='contact';
                            $message->setBody(array('model'=>$model),'text/plain');
                            $message->addTo('vlad.velici@gmail.com');
                            $message->subject=$model->subject;
                            $message->from=Yii::app()->params['contactEmail'];
                            Yii::app()->mail->send($message);

                            $message = new YiiMailMessage;
                            $message->view='copy';
                            $message->setBody(array('model'=>$model),'text/plain');
                            $message->subject='Copie a "'.$model->subject.'"';
                            $message->addTo($model->email);
                            $message->from=Yii::app()->params['contactEmail'];
                            Yii::app()->mail->send($message);

                            Yii::app()->user->setFlash('contact','Mesajul a fost trimis cu succes. Vă vom contacta în cel mai scurt timp posibil. Vă mulțumim! <br /><br />Veți primi un e-mail conținând mesajul trimis.');
                            $this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	public function actionDev () {
		$this->render('dev');
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->homepage);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
        
        public function actionGetClassSubjects($class) {
            $class = Classes::model()->with('rSubjects')->findByPk((int) $class);
            echo $class->name;
            $subjects = $class->rSubjects;
            //var_dump($subjects);
            foreach ($subjects as $subject) {
                echo $subject->name."<br />";
            }
            Yii::app()->end();
        }

        public function actionSentConfirmation() {
            if (!isset($_GET['cliMsgId'], $_GET['charge'], $_GET['status']))
                    throw new CHttpException (400, 'Invalid parameters');
//           if (Yii::app()->request->userHostAddress=='196.5.254.33') {
                $model = Sms::model()->findByPk((int)$_GET['cliMsgId']);
                if ($model === null) {
                    $t = '';
                    foreach ($_GET as $k => $v) {
                        $t .= $k . " = ". $v . "\n";
                    }
                    Yii::log('SMS Confirmation with no SMS in db. Datas received: '.$t, 'warning');
                    Yii::app()->end();
                } else {
                    if ($_GET['status']=='004' || $_GET['status']=='008') {
                        $model->status=Sms::STATUS_SENT;
                        $model->sent=time();
                        $model->charge=$_GET['charge'];
                    } else {
                        $model->status=Sms::STATUS_ERROR;
                        $model->charge=$_GET['charge'];
                    }
                    $model->save();
                }
  //          } else
    //            throw new CHttpException(403, 'Acces respins.');
                echo "done";
        }

}
