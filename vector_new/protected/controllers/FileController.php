<?php

class FileController extends Controller {

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
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
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
                'actions' => array('create'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                 'expression' => array('FileController', 'allowOnlyOwner')
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
            $example = File::model()->findByPk($_GET["id"]);
            return $example->userId === Yii::app()->user->id;
        }
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
    	throw new CHttpException(404, 'The requested page does not exist.');
        $model = new File;
        $issue = null;
        if (isset($_GET['discrepancyId'])){
            $discrepancyId = $_GET['discrepancyId'];
            $discrepancy = Nonconformity::model()->findByPk($discrepancyId);
            $step = $discrepancy->step;
            $model->discrepancyId = $discrepancyId;
            $issue = Issue::model()->find("id = $discrepancy->issueId");
            $dir = "discrepancy/";
        }elseif(isset($_GET['stepId'])){
            $stepId = $_GET['stepId'];
            $step = Step::model()->findByPk($stepId);
            $model->stepId = $stepId;
            $dir = "step/";
        }else
            throw new CHttpException(404, 'The requested page does not exist.');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['File'])) {
            $model->attributes = $_POST['File'];
            $upload = CUploadedFile::getInstance($model, 'fileSelected');
            $rnd = rand(0, 99999);
            if (@getimagesize($upload->tempName)) {
                $model->image = 1;
            } else {
                $model->image = 0;
            }
            $fileName = "{$rnd}-{$upload}";  
            $model->userId = Yii::app()->user->id;
            $model->fileSelected = $upload;
            $link = $dir.preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fileName);
            if($upload->saveAs(Yii::app()->params['dfs']."/$link")){
                $model->link = $link;
                $model->save();
                $this->redirect(array('index', 'discrepancyId' => $model->discrepancyId));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'step' =>$step,
            'issue' => $issue,
            'discrepancyId' =>$discrepancyId,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['File'])) {
            $model->attributes = $_POST['File'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $file = $this->loadModel($id);
    if($file->result){$file->result->delete();}
        $discrepancyId = $file->discrepancyId;
        if(is_null($discrepancyId)){
            $stepId = $file->stepId;
            $file->delete();
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('step/view','id'=>$stepId));
        }else{
            $file->delete();
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index','discrepancyId'=>$discrepancyId));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $discrepancyId = null;
        if (isset($_GET['discrepancyId'])){
            $discrepancyId = $_GET['discrepancyId'];
            $discrepancy = Nonconformity::model()->findByPk($discrepancyId);
            $issue = Issue::model()->find("id = $discrepancy->issueId");
            $step = $discrepancy->step;
        }else
            throw new CHttpException(404, 'The requested page does not exist.');
        if(!$discrepancy->visible){
            $this->layout = "//layouts/column1";
        }
        $dataProvider = new CActiveDataProvider('File', array(
            'criteria'=>array('condition'=>"discrepancyId=$discrepancyId")));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'step' => $step,
            'issue' => $issue,
            'discrepancy' => $discrepancy,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new File('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['File']))
            $model->attributes = $_GET['File'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return File the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = File::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param File $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'file-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
