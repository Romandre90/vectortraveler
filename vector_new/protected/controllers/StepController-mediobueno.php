<?php

class StepController extends Controller {

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
                'actions' => array('create', 'importExcelTable'),
                'expression' => "Yii::app()->user->getState('role')>1",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete', 'update', 'reorder'),
                'expression' => array('StepController', 'allowOnlyOwner')
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
            return Step::model()->findByPk($_GET["id"])->traveler->userId === Yii::app()->user->id;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $step = $this->loadModel($id);
        $issue = null;
        if (isset($_GET['issueId'])) {
            if ($_GET['issueId'] != '')
                $issue = Issue::model()->find('id=' . $_GET['issueId']);
            if ($issue) {
                if ($issue->status == 0) {
                    $this->layout = "//layouts/column1";
                }
            }
        }
        if (isset($_POST['position'])) {
            $step->sortElement($_POST['position']);
            exit("ok");
        }
        $comment = $this->createComment($step, $issue);
        $element = $this->createElement($step);
        $text = $this->createText($step);
        $subStepModel = $this->createSubStep($step);
        $file = $this->createFile($step);
        $link = $this->createLink($step);
        $this->render('view', array(
            'model' => $step,
            'comment' => $comment,
            'element' => $element,
            'file' => $file,
            'link' => $link,
            'text' => $text,
            'subStepModel' => $subStepModel,
            'issue' => $issue,
        ));
    }

    protected function createSubStep($step) {
        $subStepModel = new Step;
        if (isset($_POST['Step'])) {
            $subStepModel->attributes = $_POST['Step'];
            if ($step->addSubStep($subStepModel)) {
                Yii::app()->user->setFlash('subStepSubmitted', Yii::t('default', "Your sub step has been added"));
                $this->refresh();
            }
        }
        return $subStepModel;
    }

    public function actionReorder($id) {
        if (isset($_POST['position'])) {
            Step::model()->sortSteps($_POST['position']);
            exit("ok");
        }

        $step = $this->loadModel($id);
        $this->render('reorder', array(
            'step' => $step,
        ));
    }

    protected function createComment($step, $issue) {
        $comment = new Comment;
        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            if ($issue)
                $comment->issueId = $issue->id;
            if ($step->addComment($comment)) {
                Yii::app()->user->setFlash('commentSubmitted', Yii::t('default', "Your comment has been added"));
                $this->refresh();
            }
        }
        return $comment;
    }

    protected function createFile($step) {
        $file = new File('create');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['File'])) {
            $file->attributes = $_POST['File'];
            $stepId = $step->id;
            $file->stepId = $stepId;
            $file->attributes = $_POST['File'];
            $upload = CUploadedFile::getInstance($file, 'fileSelected');
            if (!$upload)
                return $file;
            if (@getimagesize($upload->tempName)) {
                $file->image = 1;
            } else {
                $file->image = 0;
            }
            $rnd = rand(0, 99999);
            $fileName = "{$rnd}-{$upload}";  // random number + file name
            $file->fileSelected = $upload;
            $link = "step/".preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fileName);
            $file->link = $link;
            if ($upload->saveAs(Yii::app()->params['dfs'] . "/$link")) {
                if($step->addFile($file)){
                    $element = new Element;
                    $element->fileId = $file->id;
                    $element->stepId = $step->id;
                    $element->typeId = 12;
                    $element->position = $step->elementCount + 1;
                    $element->save();
                    Yii::app()->user->setFlash('fileSubmitted', Yii::t('default', "Your file has been added"));
                    $this->refresh();
                };
            }
        }
        return $file;
    }

    protected function createElement($step) {
        $element = new Element('addElement');
        if (isset($_POST['Element']['label']) && !isset($_POST['Element']['url'])) {
            $element->attributes = $_POST['Element'];
            if ($element->typeId > 4 && $element->typeId < 8) {
                $multi = $_POST['Element']['multi'];
                $step->addElement($element, $multi);
            } else {
                if(isset($_POST['Rows']) && isset($_POST['Columns'])){
                    $step->addGrid($element,$_POST['Rows'],$_POST['Columns']);
					CHttpException(403, "lasjkd".$element);
                }else{
                    $step->addElement($element);
                }
            }
            $this->refresh();
        }
        return $element;
    }

    protected function createText($step) {
        $element = new Element('addText');
        if (isset($_POST['Element']['text'])) {
            $element->attributes = $_POST['Element'];
            $element->typeId = 10;
            $step->addElement($element);
            $this->refresh();
        }
        return $element;
    }

    protected function createLink($step) {
        $element = new Element('addUrl');
        if (isset($_POST['Element']['url'])) {
            $element->attributes = $_POST['Element'];
            $element->typeId = 11;
            $step->addElement($element);
            $this->refresh();
        }
        return $element;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Step;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Step'])) {
            $model->attributes = $_POST['Step'];
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

        if (isset($_POST['Step'])) {
            $model->attributes = $_POST['Step'];
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
        $step = $this->loadModel($id);
        if (is_null($step->parentId)) {
            $travelerId = $step->travelerId;
            $step->delete();
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('traveler/view', 'id' => $travelerId));
        }else {
            $parentId = $step->parentId;
            $step->delete();
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view', 'id' => $parentId));
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Step');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Step('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Step']))
            $model->attributes = $_GET['Step'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Step the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Step::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Step $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'step-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
	public function actionimportExcelTable($id, $issueId)	{/*  //step id
		if(isset($_FILES['Excel'])){
			$ww=$_FILES['Excel'];
			Element::model()->excelFile($ww);
			//throw new CHttpException(403,$_FILES['File']);
			Yii::import('ext.phpexcel.IOFactory');
			Yii::import('ext.phpexcel.*');
			Yii::import('ext.phpexcel.XPHPExcel');
			Yii::import('ext.phpexcel.shared.string');
			$newsheet=  XPHPExcel::createPHPExcel(); 
			$inputFileName='c:\inetpub\wwwroot\vector2014\protected\extensions\examples\reader\sampledata\book1.xlsx';
			//set_include_path(get_include_path() . PATH_SEPARATOR . 'c:\inetpub\wwwroot\vector2014\protected\extensions\phpexcel\\');
			
			
			try{
			$objPHPExcel=PHPExcel_IOFactory::load($inputFileName);
			} catch (PHPExcel_Reader_Exception $e){
				die('Error loading file: '.$e->getMessage());
				}
				
			$sheet= $objPHPExcel->getSheet(0);
			$highestRow= $sheet->getHighestRow();
			$highestColumnIndex=$sheet->getHighestColumn();

			$index=	array('A' =>1,
						'B' =>2,
						'C' =>3,
						'D' =>4,
						'E' =>5,
						'F' =>6,
						'G' =>7,
						'H' =>8);
			$highestColumn=$index[$highestColumnIndex];
			$index= array_slice($index, 0, $highestColumn);
			$element= new Element();
			$element->stepId=$id;
			$element->typeId=13;
			$element->save();
			$elementId=$element->id;
			$step= $this->loadModel($id);
			
			for ($row =1 ; $row<=$highestRow; $row++){
			
				$rows[]=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow('A',$row)->getValue();	
			}
			
			$columns=array();
			
			//for ($column =1 ; $column<=$highestColumn; $column++){
			foreach($index as $key=>$position){
				
				$columns[]=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($position,1)->getValue();
				
			}
			
			
			$salida=$step->addGrid($element, $rows, $columns);
			
			
			
			$issue = new Issue();
			//$travelerId= $step->travelerId;
			for($row =1; $row<=$highestRow; $row++){
			
				foreach($index as $key=>$position){

						if ($row>=1 && $position>0){
						$result= new Result();
						$result->elementId=$elementId;
						$result->issueId=$issueId;
						$result->value=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($position,$row)->getValue();
						//throw new CHttpException(403, 'row: '.$row.'. Columna: '.$position.'.Value: '.$result->value);
						$result->colonne=$position-1;
						$result->ligne=$row-2;
						$result->save();
						}
						//throw new CHttpException (403,"exception: ". $iasdasdeId);
						//$objPHPExcel->getActiveSheet()->getCellByColumnAndRow('A',$row)->getValue();
					
				}
			}
			$alsdjk=$LKSJAD;
		}else{ $alsdjk=$LKSJAD;
			throw new CHttpException(403,"Error in the file");
			}*/
	}
}