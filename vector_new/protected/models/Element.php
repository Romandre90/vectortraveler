<?php

/**
 * This is the model class for table "element".
 *
 * The followings are the available columns in table 'element':
 * @property integer $id
 * @property integer $typeId
 * @property string $label
 * @property string $help 
 * @property integer $stepId
 * @property string $text
 * @property string $url
 * @property integer $fileId
 * @property integer $position
 *
 * The followings are the available model relations:
 * @property Step $step
 * @property File $file
 * @property Value[] $values
 * @property Result[] $results
 */
class Element extends CActiveRecord {

    const ELEMENT_TEXT = 0;
    const ELEMENT_LONGTEXT = 1;
    const ELEMENT_UPLOAD = 2;
    const ELEMENT_LINK = 3;
    const ELEMENT_DATE = 4;
    const ELEMENT_RADIO = 5;
    const ELEMENT_CHECKBOX = 6;
    const ELEMENT_LIST = 7;
    const ELEMENT_MULTIUPLOAD = 8;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Element the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'element';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('label, stepId', 'required', 'on' => 'addElement'),
            array('text', 'required', 'on' => 'addText'),
            array('url', 'url'),
            array('url', 'required', 'on' => 'addUrl'),
            array('typeId, stepId, position, fileId', 'numerical', 'integerOnly' => true),
            array('label, help', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, text, url, typeId, fileId, help, label, stepId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'step' => array(self::BELONGS_TO, 'Step', 'stepId'),
            'file' => array(self::BELONGS_TO, 'File', 'fileId'),
            'values' => array(self::HAS_MANY, 'Value', 'elementId'),
            'results' => array(self::HAS_MANY, 'Result', 'elementId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('default', 'ID'),
            'typeId' => Yii::t('default', 'Type'),
            'label' => Yii::t('default', 'Label'),
            'stepId' => Yii::t('default', 'Step'),
            'text' => Yii::t('default', 'Text'),
            'help' => Yii::t('default', 'Help'),
            'url' => Yii::t('default', 'Url'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = "stepId = $this->stepId";
        $criteria->compare('id', $this->id);
        $criteria->compare('typeId', $this->typeId);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('url', $this->label, true);
        $criteria->compare('stepId', $this->stepId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Retrieves a list of status
     * @return array an array of available status.
     */
    public function getElementOptions() {
        return array(
            self::ELEMENT_TEXT => 'Text Field',
            self::ELEMENT_LONGTEXT => 'Text Area',
            self::ELEMENT_UPLOAD => 'Upload',
            self::ELEMENT_LINK => 'Link',
            self::ELEMENT_DATE => 'Date Picker',
            self::ELEMENT_RADIO => 'Radio',
            self::ELEMENT_CHECKBOX => 'Checkbox',
            self::ELEMENT_LIST => 'List',
            self::ELEMENT_MULTIUPLOAD => 'Multi Upload'
        );
    }

    public function getElementOptionsType() {
        if ($this->typeId < 5 OR $this->typeId == 8) {
            return array(
                self::ELEMENT_TEXT => 'Text Field',
                self::ELEMENT_LONGTEXT => 'Text Area',
                self::ELEMENT_UPLOAD => 'Upload',
                self::ELEMENT_LINK => 'Link',
                self::ELEMENT_DATE => 'Date Picker',
                self::ELEMENT_MULTIUPLOAD => 'Multi Upload'
            );
        } else {
            return array(
                self::ELEMENT_RADIO => 'Radio',
                self::ELEMENT_CHECKBOX => 'Checkbox',
                self::ELEMENT_LIST => 'List',);
        }
    }

    public function copy($stepId) {
        $values = $this->values;
        $this->id = null;
        $this->isNewRecord = true;
        $this->stepId = $stepId;
        $this->save();
        $elementId = $this->id;
        foreach ($values as $value) {
            $value->copy($elementId);
        }
    }
    
    public function getColumns() {
        return Value::model()->findAll("elementId = $this->id and colonne = 1");
    }

    public function getRows() {
        return Value::model()->findAll("elementId = $this->id and colonne = 0");
    }
    public function isExcel(){
		return Result::model()->findAll("elementId = $this->id and colonne>1 or ligne>1");
	}
    public function getResult($issueId = null) {
        if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $result = Result::model()->find($criteria);
        if ($result) {
            return $result->value;
        } else {
            return '';
        }
    }
    
    public function getResultTable($issueId = null, $col = null, $row = null){
        if(is_null($issueId) || is_null($col) || is_null($row)){
            return '';
        }
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id and colonne = $col and ligne = $row";
        $result = Result::model()->find($criteria);
        if ($result) {
            if($this->typeId==14 && $result->value=="on"){
                return 'checked';
            }else{
                return CHtml::encode($result->value);
            }
            
        } else {
            return '';
        }
    }
	public function getResultTableForExcel($issueId = null, $elementId = null, $col = null, $row = null){
        if(is_null($issueId) || is_null($col) || is_null($row)){
            return '';
			$aldjkus=$lasjkd;
        }
		
		if($issueId==null){
			$issueId="null";
		}	
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $elementId and colonne = $col and ligne = $row";
        $result = Result::model()->find($criteria);
        if ($result) {
            if($this->typeId==14 && $result->value=="on"){
                return 'checked';
            }else{
                return CHtml::encode($result->value);
            }
            
        } else {
            return '';
        }
    }

    public function getUserDate($issueId = null) {
        if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $result = Result::model()->find($criteria);
        if ($result) {
            return $result->user->username ." ". $result->dateCreated;
        } else {
            return '';
        }
    }
	public function getUserForExcel($issueId, $elementId){
		
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $elementId";
        $result = Result::model()->find($criteria);
        if ($result) {
            return $result->user->username;
        } else {
            return '';
        }
	}
	
	public function getUserDateForTables($issueId = null, $row, $column) {
        if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id and ligne = $row and colonne = $column";
        $result = Result::model()->find($criteria);
        
		//$result=Result::model()->find(
		
		if ($result) {
            return $result->user->username ."  ".  $result->dateCreated;
        } else {
            return "";
        }
    }
	
	//this method generates the user info for the title attribute
	public function getUserDateForTables2($issueId = null, $row, $column) {
        if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id and ligne = $row and colonne = $column";
        $result = Result::model()->find($criteria);
        
		//$result=Result::model()->find(
		
		if ($result) {
            return $result->user->username . ". \n " . $result->dateCreated;
        } else {
            return "";
        }
    }
	public function getUserForBox($issueId=null){
		//var_dump(empty($resultado));
		 //$values = $this->values;
		$resultado="";
		if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $results = Result::model()->findAll($criteria);
		foreach ($results as $result){
			$label=Value::model()->findByPk($result->value);
			 
			$resultado.="</br><p style='padding:4px; border-radius:3px;font-size:small; display:none;background-color:rgb(220,220,220);' class='info'>".$label->value.": ".$result->user->username." ".$result->dateCreated."</p>";;
		}
		return $resultado;
	}
	public function getUserForBox2($issueId=null){
		//var_dump(empty($resultado));
		 //$values = $this->values;
		$resultado="";
		if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $results = Result::model()->findAll($criteria);
		foreach ($results as $result){
			$label=Value::model()->findByPk($result->value);
			 
			$resultado.=$label->value.":    ".$result->user->username.".  ".$result->dateCreated."\n";
		}
		return $resultado;
	}

    public function getFile($issueId = null) {
        if (is_null($issueId))
            return false;
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $result = Result::model()->find($criteria);
        if (!$result) {
            return false;
        }
        $file = File::model()->findByPk($result->value);
        if ($file) {
            return $file;
        } else {
            return false;
        }
    }

    public function getFiles($issueId = null) {
        if (is_null($issueId))
            return false;
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $result = Result::model()->findAll($criteria);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function getResults($issueId = null) {
        if (is_null($issueId))
            return '';
        $criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $this->id";
        $result = Result::model()->findAll($criteria);
        if ($result) {
            $arr = array();
            foreach ($result as $value) {
                $arr[] = $value->value;
            }
            return $arr;
        } else {
            return '';
        }
    }

    public function reorder() {
        $elements = Element::model()->findAll("stepId = $this->stepId AND position > $this->position");
        if ($elements)
            foreach ($elements as $element) {
                $element->position--;
                $element->save();
            }
    }
	public function doesExist($elementId, $issueId, $value ){
		$criteria = new CDbCriteria;
        $criteria->condition = "issueId = $issueId and elementId = $elementId and value like '$value'";
		$modelo =Result::model()->count($criteria);
		return $modelo;
		/*
		if($modelo) return false;
		else return true;*/
	}
	/*
	public function excelFile($element, $issueId)	{
	

                $i = 0;
                $id = $element->id;
				
                foreach ($element->columns as $column) {
                    //echo '<th>' . $column->value . '</th>';
                    $i++;
                }
               // echo "</tr>";
                $r = 0;
	

				echo "<h1>PHPExcel Reader</h1>";
				//Yii::import('ext.phpexcel.XPHPExcel');
				//XPHPExcel::init();


				Yii::import('ext.phpexcel.XPHPExcel');
				$phpExcel = XPHPExcel::createPHPExcel();


				//$objPHPExcel = new PHPExcel();

				//set_include_path(get_include_path() . PATH_SEPARATOR . 'c:\inetpub\wwwroot\vector2014\protected\vendors\\');

				//include 'PHPExcel\IOFactory.php';

				$inputFileName= 'protected/extensions/examples/reader/sampledata/example1.xls';
				$phpExcel= PHPExcel_IOFactory::load($inputFileName);
				echo 'Loading file ',pathinfo($inputFileName, PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				echo '<br />';/*
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				var_dump($sheetData);
			

             
			
			
				$rowCont=0;
				$newsheet = new PHPExcel();
				foreach ($element->rows as $row){
					$rowCont++;
					$newsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCont,$row->value);
					for ($index = 0; $index < $i; $index++) {
						$res = $element->getResultTable($issueId, $index, $r);
						if ($res!=null){
							$newsheet->getActiveSheet()->setCellValueByColumnAndRow($index,$r,$res);
						}
					}
				}
				
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header ('Content-Disposition: attachment;filename="myfile.xls');
				header ('Cache-Control: max-age=0');
				
				$objWriter= PHPExcel_IOFactory::createWriter($newsheet, 'Excel2007');
				$objWriter->save('php://output');
				
		}*/		
}
