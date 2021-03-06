<?php

class MarkController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    protected $_model = null;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'markStudent + create, delete, thesis, purtare',
            'accessControl - create, delete, thesis, purtare', // perform access control for CRUD operations
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

    public function filterMarkStudent($c) {
        if (Yii::app()->user->checkAccess('admin')) {
            $c->run();
            return true;
        } else {
            if (isset($_POST['Mark']['student'])) {
                $student = Student::model()->findByPk((int) $_POST['Mark']['student'], array('select' => 'class'));
                if ($student !== null && Yii::app()->user->checkAccess('formteacher:' . $student->class) === true) {
                    $c->run();
                    return true;
                }
            } elseif (isset($_GET['id'])) {
                $mark = $this->loadModel($_GET['id']);
                if (Yii::app()->user->checkAccess('formteacher:' . $mark->rStudent->class) === true) {
                    $c->run();
                    return true;
                }
            }
        }
        throw new CHttpException(403, 'Nu puteți modifica notele sau absențele acestui elev.');
        return false;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        if (isset($_POST['Mark'])) {
            $model = new Mark;
            $model->attributes = $_POST['Mark'];
            if ($model->save()) {
                echo '1::';
                $this->renderPartial('_view', array('mark' => $model, 'adminOptions' => true));
                Yii::app()->end();
            } else {
                echo '2::';
                $this->renderPartial('_error', array('errors' => $model->getErrors()));
            }
        } else
            throw new CHttpException(400, 'Notele se adaugă din profilul elevului.');
    }

    /**
     * Adds or modifies the thesis
     * outputs 1 for success or 2 for fail
     */
    public function actionThesis() {
        if (isset($_POST['Mark'])) {
            if (isset($_POST['Mark']['mark']) && $_POST['Mark']['mark'] == 0) {
                $model = Mark::model()->getCurrentThesis($_POST['Mark']['student'], $_POST['Mark']['subject']);
                if ($model !== null)
                    $model->delete();
                echo '0';
            } else {
                $model = Mark::model()->getCurrentThesis($_POST['Mark']['student'], $_POST['Mark']['subject']);
                if ($model === null)
                    $model = new Mark;
                $model->setScenario('thesis');
                $model->attributes = $_POST['Mark'];
                if ($model->save()) {
                    echo ((int) $_POST['Mark']['mark']);
                } else {
                    echo '11';
                }
            }
            Yii::app()->end();
        } else
            throw new CHttpException(400, 'Tezele se modifică din profilul elevului.');
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
            throw new CHttpException(400, 'Cerere invalidă. Vă rugam să nu mai reptați această cerere.');
    }

    public function actionPurtare() {
        if (isset($_POST['Mark']['student'], $_POST['newpurtare'])) {
            if (Average::setPurtare($_POST['Mark']['student'], $_POST['newpurtare'])) {
                echo intval($_POST['newpurtare']);
            } else {
                throw new CHttpException(403, "A apărut o eroare.");
            }
        } else {
            throw new CHttpException(404, "A apărut o eroare.");
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        if ($this->_model === null) {
            $this->_model = Mark::model()->findByPk((int) $id);
            if ($this->_model === null) {
                throw new CHttpException(404, 'Nota nu există.');
            }
        }
        return $this->_model;
    }

}
