<?php

/**
 * This is the model class for table "result".
 *
 * The followings are the available columns in table 'result':
 * @property integer $id
 * @property integer $elementId
 * @property string $value
 * @property integer $issueId
 * @property integer $fileId
 * @property string $createTime
 * @property string $userId
 * @property integer $colonne
 * @property integer $ligne
 *
 * The followings are the available model relations:
 * @property Element $element
 * @property Equiment $issue
 * @property File $file
 * @property User $user
 */
class Result extends CActiveRecord {
public $excel=null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Result the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'result';
    }
    
    public function getDateCreated() {
        // Format dates based on the locale
        return Yii::app()->dateFormatter->format("HH:mm",$this->createTime)." ".Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat,$this->createTime);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('elementId, colonne, ligne, issueId, fileId, userId', 'numerical', 'integerOnly' => true),
            array('value', 'length', 'max' => 500),
			//array('excel','file','types'=>'xlsx'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, elementId, colonne, ligne, fileId, value, issueId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'element' => array(self::BELONGS_TO, 'Element', 'elementId'),
            'issue' => array(self::BELONGS_TO, 'Equiment', 'issueId'),
            'file' => array(self::BELONGS_TO, 'File', 'fileId'),
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'value' =>array(self::BELONGS_TO, 'Value', 'value'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'elementId' => 'Element',
            'value' => 'Value',
            'issueId' => 'Equiment',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('elementId', $this->elementId);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('issueId', $this->issueId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeSave() {
		$this->userId = Yii::app()->user->id;
        return parent::beforeSave();
    }
    
    protected function beforeDelete() {
        if($this->file){
            $this->file->delete(false);
        }
        return parent::beforeDelete();
    }

    /**
     * Attaches the timestamp behavior to update our create and update
      times
     */
    public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'createTime',
                'updateAttribute' => 'createTime',
            ),
        );
    }
    
    public function reset($id){
        $results = Result::model()->with('element')->findAll("issueId = $id and typeId NOT IN (2,8)");
        foreach ($results as $result) {
            $result->delete();
        }
    }
	
	public function resetBox($id){
        $results = Result::model()->with('element')->findAll("issueId = $id and typeId=6");
        foreach ($results as $result) {
            $result->delete();
        }
    }
	
	
	
	
	
	
	
    public function encontrarPorId($id){
		$results = Result::model()->with('element')->findAll("issueId = $id and typeId NOT IN (2,8)");
		$keys=array();
		$i=0;
		
		foreach ($results as $resultado){
			$keys[]=$resultado->getPrimaryKey();
			
			}
			return $keys;
	}
	public function getResults($id){
		$results = Result::model()->with('element')->findAll("issueId = $id and typeId NOT IN (2,8)");
		return $results;
		}
      
      public function deleteOneResult($id){
		$result= Result::model()->with('element')->findAll("elementId = $id and typeId NOT IN (2,8)");
		$result->delete();
	  }
	
	
    public function resetTable($id){
        $results = Result::model()->with('element')->findAll("issueId = $id and typeId IN (13,14)");
        foreach ($results as $result) {
            $result->delete();
        }
    }
	public function resetEmptyTable($id){
        $results = Result::model()->with('element')->findAll("value='' and issueId = $id and typeId IN (13,14)");
        foreach ($results as $result) {
            $result->delete();
        }
    }
	public function setTableOFF($id){
		$results = Result::model()->with('element')->findAll("issueId = $id and typeId IN(13,14)");
		foreach($results as $result){
			$result->value="off";
			$result->save();
		}
	}
	public function importExcelTable(){
	
		if(isset($_FILES['Excel'])){
			$model= new Result;
			$model->attributes=$_FILES['Excel'];
			
			$model->excel=CUploadedFile::getInstanceByName('Excel');

			if($model->validate()){
			
				
				$post=$_FILES['Excel'];
				//$post=(string)$post;
				$dossier=Yii::app()->params['dfs']."/temp/";
				$dir="temp/";
				$rnd=rand(0, 99999);
				$fichier="{$rnd}-";
				if(move_uploaded_file($post['tmp_name'], $dossier.preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fichier))){
					return $dossier.preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fichier);
					} else return $dossier.$post['tmp_name'];
			}else throw new CHttpException(403, "File upload failure ");
		}else throw new CHttpException(403, "File upload failure");
	}
	
	public function cleanRecords(){
		//delete 24h older records
		Opendocs::model()->deleteAll(" createTime<=(NOW()-1)");//oracle
	}
	
}