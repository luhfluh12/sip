<?php

class AbsenceController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'absenceStudent + create, delete, authorize, saveInterval',
            'accessControl - create, delete, authorize, saveInterval', // perform access control for CRUD operations
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
                'roles' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function filterAbsenceStudent($c) {
        if (Yii::app()->user->checkAccess('admin')) {
            $c->run();
            return true;
        }
        if (isset($_POST['student']))
            $studentId = (int) $_POST['student'];
        elseif (isset($_POST['Absence']['student']))
            $studentId = (int) $_POST['Absence']['student'];

        if (isset($studentId)) {
            $student = Student::model()->findByPk((int) $_POST['Mark']['student'], array('select' => 'class'));
            if ($student !== null && Yii::app()->user->checkAccess('formteacher:' . $student->class) === true) {
                $c->run();
                return true;
            }
        } elseif (isset($_GET['id'])) {
            $absence = $this->loadModel($_GET['id']);
            if (Yii::app()->user->checkAccess('formteacher:' . $absence->rStudent->class) === true) {
                $c->run();
                return true;
            }
        }

        throw new CHttpException(403, 'Nu puteți modifica absențele acestui elev.');
        return false;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        if (isset($_POST['Absence'])) {
            $model = new Absence;
            $model->attributes = $_POST['Absence'];
            if ($model->save()) {
                echo '1::';
                $this->renderPartial('_view', array('absence' => $model, 'adminOptions' => true));
                Yii::app()->end();
            } else {
                echo '2::';
                $this->renderPartial('//mark/_error', array('errors' => $model->getErrors()));
            }
        } else
            throw new CHttpException(403, 'Absențele se adaugă din profilul elevului.');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionAuthorize($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['authorized']) && ($_POST['authorized'] == 1 || $_POST['authorized'] == 2)) {
            $model->authorized = $_POST['authorized'];
            if ($model->update(array('authorized'))) {
                echo 1;
                Yii::app()->end();
            }
        }
        echo 2;
        Yii::app()->end();
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
     * Adds an interval of absences.
     * Needs $_POST start, end and student
     * 
     */
    public function actionSaveInterval() {
        if (Yii::app()->request->isPostRequest && isset($_POST['start'], $_POST['end'], $_POST['student'])) {
            $student = Student::model()->findByPk((int) $_POST['student']);
            if ($student === null)
                CHttpException(403, 'Elevul nu există în baza de date.');
            echo json_encode(Absence::model()->saveInterval($_POST['start'], $_POST['end'], $student));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Absence::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
