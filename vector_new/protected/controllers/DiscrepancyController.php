<?php

class DiscrepancyController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    private $step = null;
    private $issue = null;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
            'stepIssueContext + create',
        );
    }

    public function filterStepIssueContext($filterChain) {
        $stepId = null;
        if (isset($_GET['stepId']))
            $stepId = $_GET['stepId'];
        else
        if (isset($_POST['stepId']))
            $stepId = $_POST['stepId'];
        $this->loadStep($stepId);
        $issueId = null;
        if (isset($_GET['issueId']))
            $issueId = $_GET['issueId'];
        else
        if (isset($_POST['issueId']))
            $issueId = $_POST['issueId'];
        $this->loadIssue($issueId);
        $filterChain->run();
    }

    protected function loadStep($stepId) {
//if the project property is null, create it based on input id
        if ($this->step === null) {
            $this->step = Step::model()->findbyPk($stepId);
            if ($this->step === null) {
                throw new CHttpException(404, 'The requested step does not exist.');
            }
        }
        return $this->step;
    }

    protected function loadIssue($issueId) {
//if the project property is null, create it based on input id
        if ($this->issue === null) {
            $this->issue = Issue::model()->find("id = $issueId");
            if ($this->issue === null) {
                throw new CHttpException(404, 'The requested step does not exist.');
            }
        }
        return $this->issue;
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'expression' => array('DiscrepancyController', 'allowOnlyOwner')
            ),
            array('allow', 
                'actions' => array('closeout'),
                'expression' => "Yii::app()->user->getState('role')>1",
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Allow only the owner to do the action
     * @return boolean whether or not the user is the owner
     */
    public function allowOnlyOwner() {
        if (Yii::app()->user->getState('role') > 2) {
            return true;
        } else {
            return Discrepancy::model()->findByPk($_GET["id"])->step->traveler->userId === Yii::app()->user->id;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $discrepancy = $this->loadModel($id);
        $this->render('view', array(
            'model' => $discrepancy,
            'issue' => $this->loadIssue($discrepancy->issueId),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Discrepancy;
        $model->status = 1;
        $model->stepId = $this->step->id;
        $issue = null;
        if (isset($_GET['issueId'])) {
            if ($_GET['issueId'] != '')
                $issue = Issue::model()->find("id =" . $_GET['issueId']);
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Discrepancy'])) {
            $model->attributes = $_POST['Discrepancy'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
            'issue' => $issue,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $issue = Issue::model()->find("id =" . $model->issueId);
        if(!$issue){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Discrepancy'])) {
            $model->attributes = $_POST['Discrepancy'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
            'issue' => $issue,
        ));
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionCloseout($id) {
        $model = $this->loadModel($id);
        $model->status = 0;
        $model->scenario = 'closeout';
        $issue = Issue::model()->find("id =" . $model->issueId);
        if(!$issue){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Discrepancy'])) {
            $model->attributes = $_POST['Discrepancy'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('closeout', array(
            'model' => $model,
            'issue' => $issue,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $discrepancy = $this->loadModel($id);
        $stepId = $discrepancy->step->id;
        $issueId = $discrepancy->issueId;
        $discrepancy->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('step/view', 'id' => $stepId, 'issueId' => $issueId));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->layout = "//layouts/column1";
        if (isset($_GET['issueId'])) {
            $issue = Issue::model()->find("id = " . $_GET['issueId']);
            $dataProvider = new CActiveDataProvider('Discrepancy', array(
                'criteria' => array('condition' => "issueId = $issue->id")));
            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'issue' => $issue,
            ));
        } else {
            $model = new Discrepancy('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['Discrepancy']))
                $model->attributes = $_GET['Discrepancy'];
            $this->render('admin', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Discrepancy('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Discrepancy']))
            $model->attributes = $_GET['Discrepancy'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Discrepancy the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Discrepancy::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Discrepancy $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'discrepancy-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
