<?php

class ResultController extends Controller {

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
                'actions' => array('create', 'update','unlockIssue'),
                'expression' => "Yii::app()->user->getState('role')>0",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
    public function actionCreate($id) {
		$this->layout = '//layouts/column1';
        $issue = $this->loadIssue($id);
        $traveler = $issue->traveler;
        $redirect = false;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Result'])) {
            $redirect = true;
            $post = $_POST['Result'];
            $issueId = $post['issueId'];
            if (isset($post['elementid'])) {
                $elementId = $post['elementid'];
                foreach ($elementId as $key => $value) {
                    if ($value != '') {
                        if (is_array($value)) {
                            foreach ($value as $option) {
                                $model = new Result;
                                $model->elementId = $key;
                                $model->issueId = $issueId;
                                $model->value = $option;
                                $model->save();
                            }
                        } else {
                            $model = new Result;
                            $model->elementId = $key;
                            $model->issueId = $issueId;
                            $model->value = $value;
                            $model->save();
                        }
                    }
                }
            }
        }
		if (isset($_POST['Table'])) {
            Result::model()->resetTable($id);
            $redirect = true;
            $tables = $_POST['Table'];
            foreach ($tables as $key => $value) {
                $elementId = $key;
                foreach ($value as $row => $array) {
                    foreach ($array as $column => $response) {
                        $res = new Result;
                        $res->elementId = $elementId;
                        $res->issueId = $issueId;
                        $res->value = $response;
                        $res->colonne = $column;
                        $res->ligne = $row;
                        $res->save();
                    }
                }
            }
        }
        if (isset($_FILES['File'])) {
            $redirect = true;
            $post = $_FILES['File'];
            $elementId = $post['name']['elementid'];
            $dossier = Yii::app()->params['dfs'] . "/result/";
            $dir = "result/";
            foreach ($elementId as $key => $value) {
                if ($value != '') {
                    $rnd = rand(0, 99999);
                    $fichier = "{$rnd}-" . $value;
                    if (isset($post['tmp_name']['elementid'][$key]))
                        if (@getimagesize($post['tmp_name']['elementid'][$key])) {
                            $image = 1;
                        } else {
                            $image = 0;
                        }
                    if (move_uploaded_file($post['tmp_name']['elementid'][$key], $dossier . preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fichier))) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                        $file = new File;
                        $file->image = $image;
                        $file->userId = Yii::app()->user->id;
                        $file->fileSelected = $value;
                        $file->link = $dir . preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fichier);

                        if ($file->save()) {
                            $fileId = $file->id;
                            $model = new Result;
                            $model->elementId = $key;
                            $model->issueId = $issueId;
                            $model->fileId = $fileId;
                            $model->value = $fileId;
                            $model->save();
                        }
                    }
                }
            }
        }

        if ($redirect)
            $this->redirect(array('issue/view', 'id' => $issueId));

        $this->render('create', array(
            'model' => new Result,
            'traveler' => $traveler,
            'issue' => $issue,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
     public function actionUpdate($id) {  //$id corresponds to issueID NOT TO id!!!!!!!!!!!!
		$keys=Result::model()->encontrarPorId($id);
		$todos=implode(" , ",$keys);
		//$keysCont=0;
		//Result::model()->encontrarPorId($id);
		$this->layout = "//layouts/column1";
        $issue = $this->loadIssue($id);
        $redirect = false;
        $issueId = $id;
		
		//checking if the document is already opened
		$opened=Opendocs::model()->find("issueId=$issueId");

		 if (isset($_POST['Result'])) {
			//Result::model()->reset($id);//RESET
            $redirect = true;
            $post = $_POST['Result'];
            if (isset($post['elementid'])) {
                $elementId = $post['elementid'];
                foreach ($elementId as $key => $value) {
                    //if ($value != '') {
                        if (is_array($value)) {
                            foreach ($value as $option) {
                                $model = new Result;
                                $model->elementId = $key;
                                $model->issueId = $issueId;
								if($model->value != $option){
                                $model->value = $option;
								$model->save();
								}
							}
                        } else {
                            $model = Result::model()->find("elementId = $key and issueId = $issueId");
                            if ($model === null) {
                                $model = new Result;
                                $model->elementId = $key;
                                $model->issueId = $issueId;
                            }
                            
							
							if($model->value != $value){
								$model->value = $value;
								$model->save();
							}
                        }
                    //}
                }
            }
        }
		 if (isset($_POST['Checks'])) {
			//Result::model()->resetBox($id);//RESET
            $redirect = true;
			$typeId=6;
            $post = $_POST['Checks'];
			$values=array();
            if (isset($post['elementid'])) {
                $elementId = $post['elementid'];
                foreach ($elementId as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $option) {
							$values[]=$option;
                            $model = new Result;
                            $model->elementId = $key;
                            $model->issueId = $issueId;
							if (in_array($option, $value)){
								$what=Element::doesExist($key, $issueId, $option );
								if($what<1){
									$model->value = $option;
									$model->save();
								}
							} 
						}
                    }
					$statement=" ";
					foreach ($values as $cond){
						
							$statement.= " and t.value NOT like '$cond'";
						
					}
					$statement= "issueId= $issueId and elementId = $key ".$statement;
					$criteria = new CDbCriteria;
						
					$criteria->condition = $statement;//"issueId = $issueId and elementId = $key and value NOT LIKE ('$values')";
					$modelo =Result::model()->findAll($criteria);
					foreach ($modelo as $borrar){
						$borrar->delete();
					}
                }       
			}
        }
		
		if (isset($_POST['Table'])) {
            //Result::model()->resetTable($id);
            //Result::model()->setTableOFF($id);
			$redirect = true;
            $tables = $_POST['Table'];
            foreach ($tables as $key => $value) {
                $elementId = $key;
                foreach ($value as $row => $array) {
                    foreach ($array as $column => $response) {
                        if($elementId!="xxx"){
							$res= Result::model()->find("elementId = $elementId and issueId = $issueId and colonne = $column and ligne = $row");
                        
							if($res===null){	
								$res = new Result;
								$res->elementId = $elementId;
								$res->issueId = $issueId;
								$res->colonne = $column;
								$res->ligne = $row;
								//if($response!=null || $response!="" || !isset($response)){
							}
							if($response!=$res->value){
								$res->value = $response;
								$res->save();
							}
							
						}
					}
                }
            }
        }
         //else Result::model()->resetTable($id);
		//resetEmptyTable($id);	
		
		
        if (isset($_FILES['File'])) {
            foreach ($_FILES['File']['name']['elementid'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if ($v != '') {
                            $rnd = rand(0, 99999);
                            $tmpname = $_FILES['File']['tmp_name']['elementid'][$key][$k];
                            $fichier = "{$rnd}-" . preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $v);
                            if (move_uploaded_file($tmpname, Yii::app()->params['dfs'] . "/result/" . $fichier)) {
                                $file = new File;
                                if (@getimagesize($tmpname)) {
                                    $image = 1;
                                } else {
                                    $image = 0;
                                }
                                $file->image = $image;
                                $file->userId = Yii::app()->user->id;
                                $file->fileSelected = $v;
                                $file->link = "result/" . $fichier;

                                if ($file->save()) {
                                    $fileId = $file->id;
                                    $res = new Result;
                                    $res->elementId = $key;
                                    $res->issueId = $issueId;
                                    $res->fileId = $fileId;
                                    $res->value = $file->id;
                                    $res->save();
                                }
                            }
                        }
                    }
                } else if ($value != '') {
                    $tmp = $_FILES['File']['tmp_name']['elementid'][$key];
                    $rnd = rand(0, 99999);
                    $fichier = "{$rnd}-" . preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $value);
                    if (@getimagesize($tmp)) {
                        $image = 1;
                    } else {
                        $image = 0;
                    }
                    if (move_uploaded_file($tmp, Yii::app()->params['dfs'] . "/result/" . $fichier)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                        $file = new File;
                        $file->image = $image;
                        $file->userId = Yii::app()->user->id;
                        $file->fileSelected = $value;
                        $file->link = "result/" . $fichier;

                        if ($file->save()) {
                            $fileId = $file->id;
                            $res = new Result;
                            $res->elementId = $key;
                            $res->issueId = $issueId;
                            $res->fileId = $fileId;
                            $res->value = $file->id;
                            $res->save();
                        }
                    }
                }
            }
        }
        
		
		if(isset($opened)&& $redirect==false){  //si no se ha modificado nada y hay item
			if($opened->userId != Yii::app()->user->id){
				
				$now=time();
				$morethanyesterday=strtotime($opened->createTime);
				$morethanyesterday=$morethanyesterday + 86400;
				
				
				if ($now<$morethanyesterday){ //si hay un registro con menos de 24h
					$user=User::model()->findByPk($opened->userId);
					$this->render('open', array('user'=>$user, 'opened'=>$opened, 'issueId'=>$issueId));
				}
			} else{
					$opened->createTime=new CDbExpression('GETDATE()');
					$opened->save();
					$this->render('update', array(
						'model' => $issue,
						'traveler' => $issue->traveler,
						'issueId' => $issueId,
					));
			}
		}else if ($redirect){  //si han habido cambios, es decir, se ha dado a guardar!!
			$opened->delete();
            $this->redirect(array('issue/view', 'id' => $issueId));
		}
		
		if(!isset($opened)){

			$model=new Opendocs;
			$model->issueId=$issueId;
			$model->userId=Yii::app()->user->id;
			$model->createTime=new CDbExpression('GETDATE()');
			$model->save();
			
			$this->render('update', array(
				'model' => $issue,
				'traveler' => $issue->traveler,
				'issueId' => $issueId,
			));
		}

	}

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        if ($model->result) {
            $model->result->delete();
        }
        $model->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Result');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Result('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Result']))
            $model->attributes = $_GET['Result'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Result the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Result::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Equipment the loaded model
     * @throws CHttpException
     */
    public function loadIssue($id) {
        $model = Issue::model()->find("id = $id");
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Result the loaded model
     * @throws CHttpException
     */
    public function loadEquimentModel($id) {
        $model = Equiment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Result $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'result-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
	public function actionunlockIssue($id){
		$opened=Opendocs::model()->find("issueId=$id");
		if($opened->delete()){
			//crea otro opendoc
			$model=new Opendocs;
			$model->issueId=$id;
			$model->userId=Yii::app()->user->id;
			$model->createTime=new CDbExpression('GETDATE()');
			$model->save();
			
			//carga issue
			$issue=$this->loadIssue($id);
			$traveler=$issue->traveler;
			$issueId=$id;
			
			
			$this->render('update', array(
					'model' => $issue,
					'traveler' => $issue->traveler,
					'issueId' => $issueId,
				));
		}
	}
	

}
