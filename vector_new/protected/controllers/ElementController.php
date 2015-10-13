<?php

class ElementController extends Controller {

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
                'actions' => array('index', 'view', 'excelFile','excelFileOutput14', 'excelComp'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'expression' => "Yii::app()->user->getState('role')>1",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'expression' => array('ElementController','allowOnlyOwner')
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
    public function allowOnlyOwner(){
        if(Yii::app()->user->getState('role')>2){
            return true;
        }
        else{
            return Element::model()->findByPk($_GET["id"])->step->traveler->userId === Yii::app()->user->id;
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
   /* public function actionCreate() {
    	$model = new Element;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Element'])) {
            $model->attributes = $_POST['Element'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }*/

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $typeId = $model->typeId;
        switch ($typeId) {
            case 10:
                $model->scenario = 'addText';
                break;
            case 11:
                $model->scenario = 'addUrl';
                break;
            default:
                $model->scenario = 'addElement';
                break;
        }
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['Element'])) {
            $model->attributes = $_POST['Element'];
            if ($model->save())
                if(isset($_POST['value'])){
                    $values = $_POST['value'];
                    foreach ($values as $key => $value) {
                        $v = Value::model()->findByPk($key);
                        $v->value = $value;
                        $v->save();
                    }
                }
                $this->redirect(array('step/view', 'id' => $model->stepId));
        }
        if (isset($_POST['File'])){
            $model = $model->file;
            $model->attributes = $_POST['File'];
            if ($model->save())
                $this->redirect(array('step/view', 'id' => $model->stepId));
        }

        $this->render('update', array(
            'model' => $model,
            'typeId' => $typeId,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $element = $this->loadModel($id);
        $stepId = $element->stepId;
        $element->reorder();
        if($element->fileId){
            $file = File::model()->find("id = $element->fileId AND stepId <> $element->stepId");
            if(!$file){
				$element->file->delete();
			}
        }
        $element->delete();

        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('step/view','id'=>$stepId));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Element');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Element('search');
        $model->unsetAttributes();  // clear any default values
        if (!isset($_GET['stepId']))
            throw new CHttpException(404, 'The requested page does not exist.');
        $model->stepId = $_GET['stepId'];
        if (isset($_GET['Element']))
            $model->attributes = $_GET['Element'];

        $this->render('admin', array(
            'model' => $model,
            'step' => Step::model()->findByPk($model->stepId),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Element the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Element::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Element $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'element-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
	public function actionexcelFile($id, $issueId)	{
		if(!isset($issueId)){
			$issueId=null;
			}
		Yii::import('ext.phpexcel.XPHPExcel');
		
		$columnCont=1;
		$rowCont=1;

		$newsheet=  XPHPExcel::createPHPExcel(); 
		
	
		$element=Element::model()->findByPk($id);
		$i = 4;
		$r = 0;
		$resCol=Value::model()->findAll("elementId=$id  and colonne=1");
		foreach ($element->columns as $colonne) {
						
			
			$newsheet->getActiveSheet()->setCellValueByColumnAndRow($columnCont,1,$colonne->value);   
			 $i++;
			$columnCont++;			  
		}
		foreach ($element->rows as $row){
		
			  $rowCont++;
			$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCont,$row->value);
			for ($index = 0; $index < $columnCont; $index++) {
				$res = $element->getResultTableForExcel($issueId, $id, $index, $r);
				if ($res!=null){
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index+1,$r+2,$res);
				}
			}
			$r++;
			
		}
	
		$columnLookup = array(
			'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13,
			'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26,
			'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8, 'i' => 9, 'j' => 10, 'k' => 11, 'l' => 12, 'm' => 13,
			'n' => 14, 'o' => 15, 'p' => 16, 'q' => 17, 'r' => 18, 's' => 19, 't' => 20, 'u' => 21, 'v' => 22, 'w' => 23, 'x' => 24, 'y' => 25, 'z' => 26
		);
		array_flip($columnLookup);
		for ($i=1; $i<count($columnCont);$i++){
			$newsheet->getActiveSheet()->getColumnDimension($columnLookup[$i])->setAutoSize(true);
		}
		
		$newsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header ('Content-Disposition: attachment;filename="myfile.xlsx');
		header ('Cache-Control: max-age=0');
		//metada:
		
		$newsheet->getProperties()->setCreator(Yii::app()->user->username);
		
		// assign worksheet name
		$issue= $this->loadIssue($issueId);
		if(isset($issue)){
					$traveler=Traveler::model()->findByPk($issue->travelerId);
					//$newsheet->getActiveSheet()->setTitle($traveler->name);
					$newsheet->getActiveSheet()->setTitle($issueId);
				}
		
		//create and send
		$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
		$objWriter->save('php://output');
		
		
		
		}
	public function actionexcelFileOutput14($id, $issueId)	{

		Yii::import('ext.phpexcel.XPHPExcel');
		
		$columnCont=1;
		$rowCont=1;

		$newsheet=  XPHPExcel::createPHPExcel(); 
		

		$element=Element::model()->findByPk($id);
		$i = 4;
		$r = 0;
		$resCol=Value::model()->findAll("elementId=$id  and colonne=1");
		foreach ($element->columns as $colonne) {
									
			
			$newsheet->getActiveSheet()->setCellValueByColumnAndRow($columnCont,1,$colonne->value);   
			 $i++;
			$columnCont++;			  
		}
		foreach ($element->rows as $row){
		
			$rowCont++;
			$newsheet->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCont,$row->value);
			for ($index = 0; $index < $columnCont; $index++) {
				$res = $element->getResultTableForExcel($issueId, $id, $index, $r);
				switch ($res){
					case "off":
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index+1,$r+2,"No");
					break;
					case "checked":
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index+1,$r+2,"Yes");
					break;
					default:
				}
			}
			$r++;
		}
		$newsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$newsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header ('Content-Disposition: attachment;filename="myfile.xlsx');
		header ('Cache-Control: max-age=0');
		
		
		$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
		$objWriter->save('php://output');
				
	}
	

	
//exportando todas las tablas de un issue (13,14)

    public function loadIssue($id) {
        $model = Issue::model()->find("id = $id");
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
	
}

