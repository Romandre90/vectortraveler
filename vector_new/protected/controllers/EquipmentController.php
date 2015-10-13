<?php

class EquipmentController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $projects = null;
    public $components = null;

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
                'actions' => array('index', 'view', 'assembly','attached','updateajax'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'issue', 'preferences'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'attach', 'update','close'),
                'expression' => array('EquipmentController', 'allowOnlyOwner')
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('reorder'),
                'expression' => "Yii::app()->user->getState('role')>1",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('unclose'),
                'expression' => "Yii::app()->user->getState('role')==4",
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionReorder($id) {
        if (isset($_POST['position'])) {
            Equipment::model()->sortEquipments($_POST['position']);
            exit("ok");
        }
        $this->render('reorder', array(
            'component' => Components::model()->findByPk($id), 
        ));
    }
    /**
     * Allow only the owner to do the action
     * @return boolean whether or not the user is the owner
     */
    public function allowOnlyOwner() {
        if (Yii::app()->user->getState('role') > 2) {
            return true;
        } else {
            $example = Equipment::model()->findByPk($_GET["id"]);
            return $example->userId === Yii::app()->user->id;
        }
    }

    public function actionClose($id){
        $equipment = $this->loadModel($id);
        $equipment->status = 0;
        foreach ($equipment->issues as $issue) {
            $issue->close();
        }
        $equipment->save();
        $this->redirect(array('view', 'id' => $equipment->id));
    }
    
    public function actionUnclose($id){
        $equipment = $this->loadModel($id);
        $equipment->status = 1;
        $equipment->save();
        $this->redirect(array('view', 'id' => $equipment->id));
    }
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
	
        $equipment = $this->loadModel($id);
        $travelersDataProvider = new CActiveDataProvider('Traveler', array(
            'criteria' => array(
                'with' => array('equipments' => array(
                        'condition' => "equipmentId = $id", 'together' => true
                    )),
            ),
                )
        );
		
        $issue = $this->createIssue($equipment);
		
        $this->render('view', array(
            'model' => $equipment,
            'travelersDataProvider' => $travelersDataProvider,
            'component' => $equipment->component,
            'issue'=>$issue,
            
        ));
		
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $model = new Equipment;
        $model->status = 1;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Equipment'])) {
            $model->attributes = $_POST['Equipment'];
            if ($model->save())
                if (isset($_POST['component'])) {
                    $this->redirect(array('components/view', 'id' => $model->componentId));
                } else {
                    $this->redirect(array('view', 'id' => $model->id));
                }
        }
        $component = false;
        if (isset($_GET['componentId'])) {
            $componentId = $_GET['componentId'];
            $component = Components::model()->findByPk($componentId);
            if (!$component)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        $this->render('create', array(
            'model' => $model,
            'component' => $component,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAttached($id) {
        $model = $this->loadModel($id);
        $dataProvider = new CActiveDataProvider('Equipment', array(
            'criteria' => array('condition' => "parentId=$id")));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $this->render('attached', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAttach($id) {
        if (isset($_POST['equipmentId'])) {
            $equipment = Equipment::model()->findByPk($_POST['equipmentId']);
            if($equipment->parentId){
                $equipment->parentId = null;
                $return = 0;
            }else{
                $equipment->parentId = $id;
                $return = 1;
            }
            $equipment->save();
            exit($return);
        }
        
        $search = new Equipment('search');
        $search->unsetAttributes();  // clear any default values
        if (isset($_GET['Equipment']))
            $search->attributes = $_GET['Equipment'];

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        

        $this->render('attach', array(
            'model' => $model,
            'id' => $id,
            'componentId' => $model->componentId,
            'search' => $search,
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

        if (isset($_POST['Equipment'])) {
            $model->attributes = $_POST['Equipment'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
            'component' => false,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->layout = "//layouts/column3";
        $criteria = new CDbCriteria();
        $criteria->join="JOIN components c ON projectId = t.id JOIN equipment ON componentId = c.id";
        $criteria->order='t.position';
        $criteria->distinct = true;
        $this->projects = Project::model()->findAll($criteria);
        $criteria2 = new CDbCriteria();
        $criteria2->join="JOIN equipment ON componentId = t.id";
        $criteria2->distinct = true;
        $this->components = Components::model()->findAll($criteria2);
        $this->render('index', array(
            'query' => Equipment::model()->getEquipmentsForTree(),
            'equipments' => Equipment::model()->with('component')->findAll(array('order' => 'projectId, componentId, t.id')),
        ));
    }

    /**
     * Lists all models.
     */
    public function actionAssembly() {
        $this->layout = "//layouts/column3";
        $criteria = new CDbCriteria();
        $criteria->join="JOIN components c ON projectId = t.id JOIN equipment ON componentId = c.id";
        $criteria->distinct = true;
        $this->projects = Project::model()->findAll($criteria);
        
        $criteria2 = new CDbCriteria;
        $criteria2->condition = "parentId IS NULL";
        $criteria2->with = "component";
        $criteria2->order = "projectId,componentId";
        $this->render('assembly', array(
            'equipments' => Equipment::model()->findAll($criteria2),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Equipment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Equipment']))
            $model->attributes = $_GET['Equipment'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Equipment the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Equipment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionPreferences() {
        $project = $_POST['project'];
        $userId = Yii::app()->user->id;
        $preference = Preference::model()->find("hideProject = '$project' AND userId = $userId");
        if ($preference) {
            $preference->delete();
            echo "deletion ok";
        } else {
            $model = new Preference;
            $model->hideProject = $_POST['project'];
            if ($model->save()) {
                echo "insertion ok";
            } else {
                throw new CHttpException(404, 'Error.');
            };
        }
    }

    /**
     * Performs the AJAX validation.
     * @param Equipment $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'equipment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    protected function createIssue($equipment) {
        $issue = new Issue;
        $issue->status = 1;
        $issue->createTime = new CDbExpression('NOW()');
        $issue->userId = Yii::app()->user->id;
        if (isset($_POST['Issue'])) {
            $issue->attributes = $_POST['Issue'];
            if($equipment->addIssue($issue)) {
                Yii::app()->user->setFlash('issueSubmited', Yii::t('default', "Your issue has been added"));
                $this->refresh();
            }
            
        }
        return $issue;
    }

	public function actionUpdateAjax(){
		$data=array();
		$data["myValue"]="Content updated in AJAX";
		$this->renderPartial('_ajaxContent',$data, false, true);
	
	}
}
