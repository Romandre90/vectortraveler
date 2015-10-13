<?php

class IssueController extends Controller
{
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
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete','publish'),
                'expression' => array('IssueController', 'allowOnlyOwner')
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('unpublish'),
                'expression' => "Yii::app()->user->getState('role')==4",
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
            $example = Issue::model()->find("id =".$_GET["id"]);
            return $example->userId === Yii::app()->user->id;
        }
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {

        $this->render('view', array(
            'model' => $this->loadIssue($id),
        ));
		
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Equipment the loaded model
     * @throws CHttpException
     */
    public function loadEquipment($id) {
        $model = Equipment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id) {
        $equipment = $this->loadEquipment($id);
        $model = new Issue;
        $model->equipmentId = $id;
        $model->status = 1;
        $model->createTime=new CDbExpression('GETDATE()');
        $model->userId = Yii::app()->user->id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if ($model->save())
                $this->redirect(array('result/create', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
            'equipment' => $equipment,
        ));
    }
    
    public function loadIssue($id) {
        $model = Issue::model()->find("id = $id");
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $issue = $this->loadIssue($id);
        $equipmentId = $issue->equipmentId;
        $issue->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('equipment/view','id'=>$equipmentId));
    }
    
    public function actionPublish($id){
        $issue = $this->loadIssue($id);
        $issue->status = 0;
        $issue->closedTime =new CDbExpression('NOW()');
        $equipmentId = $issue->equipmentId;
        if($issue->save()){
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('equipment/view','id'=>$equipmentId));
        }else{
            CVarDumper::dump($issue->attributes,10,true);
            CVarDumper::dump($issue->errors,10,true);
        }
    }
    
    public function actionUnpublish($id){
        $issue = $this->loadIssue($id);
        $issue->status = 1;
        $model->closedTime =NULL;
        $equipmentId = $issue->equipmentId;
        if($issue->save()){
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('equipment/view','id'=>$equipmentId));
        }else{
            CVarDumper::dump($issue->attributes,10,true);
            CVarDumper::dump($issue->errors,10,true);
        }
    }

}