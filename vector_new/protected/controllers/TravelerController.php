<?php

class TravelerController extends Controller {

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
                // 'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'view', 'admin','modification'),
                'users' => array('*'),
            ),
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('preferences'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'revision', 'modify'),
                'expression' => "Yii::app()->user->getState('role')>1",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('update','delete', 'publish', 'reorder'),
                'expression' => array('TravelerController', 'allowOnlyOwner')
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
            $example = Traveler::model()->findByPk($_GET["id"]);
            return $example->userId === Yii::app()->user->id;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $traveler = $this->loadModel($id);
        if(isset($_POST['stepImportForCreators'])){
            $step1 = Step::model()->findAllByPk($_POST['stepImportForCreators']);
            if($step1){
				foreach ($step1 as $each_step1){
					$each_step1->copy($id);
				}
			}
        }
		if(isset($_POST['stepImport'])){
			$step1 = Step::model()->findByPk($_POST['stepImport']);
			if ($step1)$step1->copy($id);
			}
        if(isset($_POST['stepDuplicate'])){
            $step2 = Step::model()->findByPk($_POST['stepDuplicate']);
            if($step2)$step2->copy($id);
        }
		if(isset($_POST['travelerImport'])){
            $traveler1 = Traveler::model()->findByPk($_POST['travelerImport']);
            if($traveler1)$traveler1->copy($id);
        }
        $step = $this->createStep($traveler);
        $this->render('view', array(
            'model' => $traveler,
            'step' => $step,
        ));
    }

    public function actionReorder($id){
        $traveler = $this->loadModel($id);
        if(isset($_POST['position'])){
            Step::model()->sortSteps($_POST['position']);
            exit("ok");
        }
        $this->render('reorder', array(
           'steps' => $traveler->getStepParent(),
           'id'=>$id,
        ));
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Traveler;
        $componentId = false;
        $model->userId = Yii::app()->user->id;
        if(isset($_GET['componentId'])){
            $componentId = $_GET['componentId'];
        }
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Traveler'])) {
            $model->attributes = $_POST['Traveler'];
            $model->status = 1;
            $model->revision = 0;
            if ($model->save()){
                $model->rootId = $model->id;
                $model->save();
                $this->redirect(array('view', 'id' => $model->id));
            }
                
        }
        
        $this->render('create', array(
            'model' => $model,
            'componentId' => $componentId,
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
        if (isset($_POST['Traveler'])) {
            $model->attributes = $_POST['Traveler'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionModification($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $this->render('modification', array(
            'model' => $model,
        ));
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionRevision($id) {
        $traveler = $this->loadModel($id);
        $parentId = $traveler->parentId ? $traveler->parentId : $traveler->id;
        $traveler->isNewRecord = true;
        $traveler->id = null;
        $traveler->rootId = $parentId;
        $traveler->parentId = $parentId;
        $traveler->revision = $traveler->nextRevision;
        $traveler->status = 1;
        
        if (isset($_POST['Traveler'])) {
            $traveler->modification = $_POST['Traveler']['modification'];
            if($traveler->save()){
                $oldModel = $this->loadModel($id);
                $oldModel->status = -1;
                $oldModel->save();
                foreach ($oldModel->stepParent as $step){
                    $step->copy($traveler->id);
                }
                $this->redirect(array('view', 'id' => $traveler->id));
            }else{
                throw new CHttpException(404, 'Error Creating Revision.');
            }
        }
        
         $this->render('revision', array(
                'model' => $traveler,
                'id' => $id,
            ));
        
        
    }
	public function actionModify($id){
		$traveler=$this->loadModel($id);
		
		if(isset($_POST['Traveler'])) {
			 $traveler->modification = $_POST['Traveler']['modification'];
			if($traveler->save()){
				 $this->redirect(array('view', 'id' => $traveler->id));
			} else{
				throw new CHttpException(404, 'Error Modifying Revision.');
			}
		}
		$this->render('revision', array(
                'model' => $traveler,
                'id' => $id,
            ));
	}

    public function actionPreferences() {
		$project = $_POST['project'];
		$hide = $_POST['hide'];
        $userId = Yii::app()->user->id;
		$preference = Preference::model()->find("hideProject = '$project' AND userId = $userId");
		if(!isset($hide)){
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
				}
			}
		}
		else{
			if($hide==0){
				if ($preference){
					$preference->delete();
					echo "deletion ok";
				}
			}
			else{
				//throw new CHttpException(666, 'VEDIAMO COSA SCRIVE');
				if(!$preference){
					$model = new Preference;
					$model->hideProject = $project;
					if ($model->save()) {
						echo "insertion ok";
					} else {
						throw new CHttpException(404, 'Error.');
					}
				}
			}
		}
    }
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $traveler = $this->loadModel($id);
        $prevTraveler = $traveler->getPreviousTraveler();
        if ($prevTraveler) {
            $prevTraveler->status = 0;
            $prevTraveler->save();
        }
        $traveler->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionPublish($id) {
        $traveler = $this->loadModel($id);
        $traveler->status = 0;
        if($traveler->save()){
            if($traveler->parentId){
                $prevTraveler = $traveler->previousTraveler;
                $prevTraveler->status = 2;
                $prevTraveler->save();
            }
            $this->redirect(array('index'));
        }else{
            throw new CHttpException(404, 'Error Publishing Traveler.');
        };
        
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->layout = "//layouts/column3";
        $criteria = new CDbCriteria();
        $criteria->join="JOIN components c ON projectId = t.id JOIN traveler ON componentId = c.id";
        $criteria->order="t.position";
        $criteria->distinct = true;
        $this->projects = Project::model()->findAll($criteria);
        $criteria2 = new CDbCriteria();
        $criteria2->join="JOIN traveler ON componentId = t.id";
        $criteria2->distinct = true;
        $this->components = Components::model()->findAll($criteria2);
        $this->render('index', array(
            'query' => Traveler::model()->getTravelerForTree(),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Traveler('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Traveler']))
            $model->attributes = $_GET['Traveler'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Search all models.
     */
    public function actionSearch() {
        $model = new Traveler('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Traveler']))
            $model->attributes = $_GET['Traveler'];

        $this->render('search', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Traveler the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Traveler::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Traveler $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'traveler-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function createStep($traveler) {
        $step = new Step;
        if (isset($_POST['Step'])) {
            $step->attributes = $_POST['Step'];
            if ($traveler->addStep($step)) {
                Yii::app()->user->setFlash('stepAdded', Yii::t('default',"Your step has been added"));
                $this->refresh();
            }
        }
        return $step;
    }
	/*
	* Método para importar un traveler entero
	*/
	public function createTraveler(){
		$traveler= new Traveler;
		if (isset($_POST['Traveler'])){
			$traveler->attributes= $_POST['Traveler'];
			if ($traveler->addStep($step)) {
                Yii::app()->user->setFlash('stepAdded', Yii::t('default',"Your step has been added"));
                $this->refresh();
            }
		}
	}
			
    
}
