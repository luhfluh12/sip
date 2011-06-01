<?php
class SubjectController extends Controller
{
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',
		);
	}
        public function accessRules() {
            return array(
                array('allow',
                        'actions'=>array('hint'),
                        'users'=>array('@'),
                    ),
            );
        }
        
	public function actionHint($term)
	{
		echo Subject::model()->autoComplete($term);
                Yii::app()->end();
	}
}