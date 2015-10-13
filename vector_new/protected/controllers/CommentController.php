<?php

class CommentController extends Controller {

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
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'expression' => array('CommentController', 'allowOnlyOwner')
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
        if (Yii::app()->user->getState('role') == 4) {
            return true;
        } else {
            return Comment::model()->findByPk($_GET["id"])->userId === Yii::app()->user->id;
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Comment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            echo CActiveForm::validate(array($model));
            Yii::app()->end();
        }

        if (isset($_POST['Comment'])) {
            $model->attributes = $_POST['Comment'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
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

        if (isset($_POST['Comment'])) {
            $model->attributes = $_POST['Comment'];
            if ($model->save())
                $this->redirect(array('index', 'travelerId' => $model->step->travelerId));
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
        $comment = $this->loadModel($id);
        $travelerId = $comment->step->travelerId;
        $issueId = $comment->issueId;
        $comment->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if($issueId){
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index','issueId'=>$issueId));
        }else
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'travelerId' => $travelerId));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->layout = "//layouts/column1";
        $issue = null;
        if(isset($_GET['issueId'])){
            $issueId = $_GET['issueId'];
            $issue = Issue::model()->find("id = $issueId");
        }else{
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $dataProvider = new CActiveDataProvider('Comment', array(
            'criteria' => array(
                'condition'=>"issueId = $issueId",
                'order'=>'stepId',
                )
            ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'issue' => $issue,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $travelerId = null;
        if (isset($_GET['travelerId']))
            $travelerId = $_GET['travelerId'];
        else
            throw new CHttpException(404, 'The requested page does not exist.');

        $model = new Comment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Comment']))
            $model->attributes = $_GET['Comment'];

        $this->render('admin', array(
            'model' => $model,
            'travelerId' => $travelerId,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Comment the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Comment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Comment $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
