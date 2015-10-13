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
                'actions' => array('index', 'view', 'loadImage', 'importExcelTable', 'exportPdfFile', 'excelComp', 'importExcelTableTraveler'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create'),
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
        if (isset($_POST['Step']['parentId'])) {
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
            //$file->attributes = $_POST['File'];
            $upload = CUploadedFile::getInstance($file, 'fileSelected');
            if (!$upload)
                return $file;
            if (@getimagesize($upload->tempName)) { //pide las propiedades de la imagen, sin control de errores, y si las devuelve es que es una imagen
                $file->image = 1;
            } else {
                $file->image = 0;
            }
            $rnd = rand(0, 99999);
            $fileName = "{$rnd}-{$upload}";  // random number + file name
            $file->fileSelected = $upload;
            $link = "step/".preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fileName);
            $file->link = $link;
            if ($upload->saveAs(Yii::app()->params['dfs'] ."/$link")) {
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
            } else throw new CHttpException (402, Yii::app()->params['dfs'] . "/$link");
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
	public function actionimportExcelTable(int $id, int $issueId)	{  //step id
		if(isset($_FILES['Excel'])){
			
			$post=Result::model()->importExcelTable($_FILES['Excel']);
			//var_dump($post);
			Yii::import('ext.phpexcel.IOFactory');
			Yii::import('ext.phpexcel.*');
			Yii::import('ext.phpexcel.XPHPExcel');
			Yii::import('ext.phpexcel.shared.string');
			$newsheet=  XPHPExcel::createPHPExcel(); 
			$inputFileName=$post;

			
			$reader=PHPExcel_IOFactory::createReader('Excel2007');
			
			if(!$reader->canRead($inputFileName)){
				throw new CHttpException(403,"File not valid");
			}
			$objPHPExcel=PHPExcel_IOFactory::load($inputFileName);
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
			foreach($index as $key=>$position){
				$columns[]=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($position,1)->getValue();
			}
			
			
			$salida=$step->addGrid($element, $rows, $columns);
			
			

			$issue = new Issue();
			for($row =1; $row<=$highestRow; $row++){
				foreach($index as $key=>$position){
					if ($row>=1 && $position>0){
					
						$result= new Result();
						$result->elementId=$elementId;
						$result->issueId=$issueId;
						$result->value=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($position,$row)->getValue();
						
						$result->colonne=$position-1;

						$result->ligne=$row-2;
						//throw new CHttpException(403, 'row: '.$result->ligne.'. Columna: '.$result->colonne.'.Value: '.$result->value.'   issueId:'.$issueId. '   elementId:'.$elementId);
						if($result->ligne>=0){
							$result->save();
						}
					}
				}
			}
			$issue= $this->loadIssue($issueId);
			$this->redirect(array('/step/view','id'=>$id, 'issueId'=>$issueId)); //this one
		}else{
			throw new CHttpException(403,"Failure loading file");
		}
	}

	
	
	public function actionimportExcelTableTraveler($id){		//step id
		if(isset($_FILES['Excel'])){
			
			$post=Result::model()->importExcelTable($_FILES['Excel']);

			Yii::import('ext.phpexcel.IOFactory');
			Yii::import('ext.phpexcel.*');
			Yii::import('ext.phpexcel.XPHPExcel');
			Yii::import('ext.phpexcel.shared.string');
			$newsheet=  XPHPExcel::createPHPExcel(); 
			$inputFileName=$post;

			
			$reader=PHPExcel_IOFactory::createReader('Excel2007');
			
			if(!$reader->canRead($inputFileName)){
				throw new CHttpException(403,"File not valid");
			}
			$objPHPExcel=PHPExcel_IOFactory::load($inputFileName);
			$sheet= $objPHPExcel->getSheet(0);
			$highestRow= $sheet->getHighestRow();
			$highestColumnIndex=$sheet->getHighestColumn();
			if ($highestRow>40){
				throw new CHttpException(403,"Rows limit exceed. ");
				}
			
			$index=	array('A' =>1,
						'B' =>2,
						'C' =>3,
						'D' =>4,
						'E' =>5,
						'F' =>6,
						'G' =>7,
						'H' =>8);

			if ($highestColumnIndex>'H'){
				throw new CHttpException(403,"Column limit exceed. ");
				}
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
			foreach($index as $key=>$position){
				$columns[]=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($position,1)->getValue();
			}
			
			
			$salida=$step->addGrid($element, $rows, $columns);
			
			//$issue= $this->loadIssue($issueId);
			$this->redirect(array('/step/view','id'=>$id)); //this one
		}else{
			throw new CHttpException(403,"Failure loading file");
		}
	}

	
	
	
	
	
	public function actionexcelComp($id, $elementId){  //issueId and elementId
		
		
		
		$element=Element::model()->findByPk($elementId);
		
		
					Yii::import('ext.phpexcel.XPHPExcel');

				$title=Element::model()->findByPk($elementId);
				$newsheet=  XPHPExcel::createPHPExcel(); 	
	
		switch ($element->typeId){
		
			case 0:
			case 1:
			case 3:
			case 4:
			

				$criteria="elementId=$elementId";
				$results=Result::model()->findAll($criteria);
				//$results=Result::model()->findAll($title->label);

				$newsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				
				

				
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Issue ID");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,1,"User");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Fill date");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(3,1,$title->label);
				
				
				$newsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



				$newsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				
				$cont=2;
				foreach($results as $result){
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$cont,$result->issueId);
					
					
					$issue= $this->loadIssue($result->issueId);
					$user=Element::model()->getUserForExcel($result->issueId, $elementId);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$cont,$user);
					//
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(2,$cont,Yii::app()->dateFormatter->format("HH:mm d/M/y",$result->createTime));
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(3,$cont,$result->value);
					$cont++;
				}
				$traveler=Traveler::model()->findByPk($issue->travelerId);
				
				if (isset($issue)){
						if(strlen($traveler->name)>28){
							$traveler->name=substr($traveler->name, 0,28);
						}
					
					$newsheet->getActiveSheet()->setTitle($traveler->name);
				} //else throw new CHttpException(403, 'There is a problem with the data');
				
				
				
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header ('Content-Disposition: attachment;filename="myfile.xlsx');
				header ('Cache-Control: max-age=0');
				//metada:
				
				$newsheet->getProperties()->setCreator(Yii::app()->user->username);
				
				$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
				$objWriter->save('php://output');
			break;
			case 5: //devuelve el value de result, que es el id de value!!
			
				//center 1st column
				$newsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
			
				//name first column
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Issue ID");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,1,"User");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Fill date");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(3,1,$title->label);
				
				
				//center data cells
				$newsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				
				
				//color
				$newsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				
				
				//size
				$newsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				
				
				
				$criteria="elementId=$elementId";
				
				
				
				$preresults=Result::model()->findAll($criteria);

				$cont=2;
			
				foreach ($preresults as $preresult){
					$issue= $this->loadIssue($preresult->issueId);
					$user=Element::model()->getUserForExcel($preresult->issueId, $elementId);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$cont,$preresult->issueId);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$cont,$user);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(2,$cont,Yii::app()->dateFormatter->format("HH:mm d/M/y",$preresult->createTime));
					$value=Value::model()->findByPk($preresult->value);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(3,$cont,$value->value);

					$cont++;
				}
				if(isset($issue)){
					$traveler=Traveler::model()->findByPk($issue->travelerId);
				
				$newsheet->getActiveSheet()->setTitle($traveler->name);
				}
				
				

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header ('Content-Disposition: attachment;filename="myfile.xlsx');
				header ('Cache-Control: max-age=0');
				//metada:
				
				$newsheet->getProperties()->setCreator(Yii::app()->user->username);
				
				$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
				$objWriter->save('php://output');
			break;
			case 6:
			case 7:
				
				//center 1st column
				$newsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$newsheet->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
			
				//name first column
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Issue ID");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,1,"User");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Fill date");
				$newsheet->getActiveSheet()->setCellValueByColumnAndRow(3,1,$title->label);
				
				
				//center data cells
				$newsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$newsheet->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				
				
				//color
				$newsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				$newsheet->getActiveSheet()->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('AFDBFF');
				
				
				//size
				$newsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$newsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				

				//saber numero de issues
				$criteria = new CDbCriteria();
				$criteria->distinct=true;
				$criteria->condition="elementId=".$elementId;
				$criteria->select= "issueId";
				$issues=Result::model()->findAll($criteria);
				$contCol=0;
				$cont=array();
				$cont=1;
				
				//looping through results by issues!!!
				foreach($issues as $issue){
					$user=null;
					$cont++;
					$results=Result::model()->findAll("elementId=$elementId and issueId=$issue->issueId");
					$contCol=3;
					$newest= Date('1970-01-21 00:00:00.0');
					foreach ($results as $result){
						//$user=Element::model()->getUserForExcel7($result->issueId, $elementId, $result->value);
						//$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1+$userColumn,$cont+1,$user);
						
						$date=$result->createTime;
						if($date>$newest){
							$newest=$date;
							$user=$result->userId;
							$user=Element::model()->getUserForExcel($result->issueId, $elementId);
						}
						if (!is_numeric($result->value)){
							$newsheet->getActiveSheet()->setCellValueByColumnAndRow($contCol,$cont,"NULL");
							$contCol++;
							$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$cont,$result->issueId);
						} else {
							$criteria="elementId=$elementId and id=$result->value";
							$values=Value::model()->find($criteria);
							$newsheet->getActiveSheet()->setCellValueByColumnAndRow($contCol,$cont,$values->value);
							$contCol++;
							$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$cont,$result->issueId);
						} 
						//else  throw new CHttpException(404, 'There is a problem with the data.');
						
						$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$cont,$user);
					}
					$newest=Yii::app()->dateFormatter->format("HH:mm d/M/y",$newest);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$cont,$user);
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(2,$cont,$newest);
					/*
					
					//esto es para cargar el nombre del traveler en el nombre de la hoja de calculo, no funciona porque no está bien cargado el issue
					if(isset($issue)){
						$traveler=Traveler::model()->findByPk($issue->travelerId);
					
						$newsheet->getActiveSheet()->setTitle($traveler->name);
					}*/
					
				}

				
				if($contCol<=26 && $contCol>0){
					$columnLookup = array(
					1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h', 9 => 'i', 10 => 'j', 11 => 'k', 12=> 'l', 13=>'m',
					14 => 'n', 15 => 'o', 16 => 'p', 17 => 'q', 18 => 'r', 19 => 's', 20 => 't', 21 => 'u', 22 => 'v', 23 => 'w', 24 => 'x', 25 => 'y', 26 => 'z'
					);
					
					
					$mergeCells="d1:".$columnLookup[$contCol]."1";
					$newsheet->setActiveSheetIndex(0)->mergeCells($mergeCells);
				}
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header ('Content-Disposition: attachment;filename="myfile.xlsx');
				header ('Cache-Control: max-age=0');
				//metada:
				
				$newsheet->getProperties()->setCreator(Yii::app()->user->username);
				
				$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
				$objWriter->save('php://output');
				break;
			case 13:
			case 14:
				
				$criteria = new CDbCriteria();
				$criteria->distinct=true;
				$criteria->condition="elementId=".$elementId;
				$criteria->select= "issueId";
				$issues=Result::model()->findAll($criteria);
				$sheetNumber=0;
				foreach ($issues as $issue){
					
					$myWorkSheet = new PHPExcel_Worksheet ($newsheet, $issue->issueId);
					$newsheet->addSheet($myWorkSheet);
					$newsheet->setActiveSheetIndex($sheetNumber+1);
					$sheetNumber++;
					
					$criteria="elementId=$elementId";
					$results=Result::model()->findAll($criteria);

					$cont=1;
					$contCol=0;		

					
					$columnCont=1;
					$rowCont=1;

					$element=Element::model()->findByPk($elementId);
					$i = 4;
					$r = 0;
					$resCol=Value::model()->findAll("elementId=$elementId  and colonne=1");
					foreach ($element->columns as $colonne) {
						$newsheet->getActiveSheet()->setCellValueByColumnAndRow($columnCont,1,$colonne->value);   
						$i++;
						$columnCont++;			  
					}
					foreach ($element->rows as $row){
					
						$rowCont++;
						$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCont,$row->value);
						for ($index = 0; $index < $columnCont; $index++) {
						
							$res = $element->getResultTableForExcel($issue->issueId, $elementId, $index, $r);
							
							switch ($res){
							
								case "off":
								
								$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index+1,$r+2,"No");
								break;
								case "checked":
								
								$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index+1,$r+2,"Yes");
								break;

								default:
								
								$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index+1,$r+2,$res);
							}
						}
						$r++;
					}
					
					$newsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$newsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					$newsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$newsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
					$newsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					
				}
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml0sheet');
				header ('Content-Disposition: attachment;filename="myfile.xlsx');
				header ('Cache-Control: max-age=0');
				
				$newsheet->removeSheetByIndex(0);
				$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
				$objWriter->save('php://output');
				break;

		
		}
	}
    public function loadIssue($id) {
        $model = Issue::model()->find("id = $id");
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
	
	public function actionloadImage($id){
		$file=File::model()->findByPk($id);
		//throw new CHttpException(404, 'The requested page does not exist.');
		sleep(2);
		$this->renderPartial('image', array('id'=>$file->link));
		Yii::app()->end();
	}
	
}