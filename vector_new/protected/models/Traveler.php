<?php

/**
 * This is the model class for table "traveler".
 *
 * The followings are the available columns in table 'traveler':
 * @property integer $id
 * @property integer $userId
 * @property string $createTime
 * @property string $updateTime
 * @property string $modification
 * @property string $name
 * @property integer $revision
 * @property integer $status
 * @property integer $parentId
 * @property integer $componentId
 * @property integer $rootId
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Components $component
 * @property Project $project
 * @property Equiment[] $equipments
 * @property Traveler $parent
 * @property Traveler[] $travelers
 * @property Step[] $steps
 */
class Traveler extends CActiveRecord {

    const STATUT_REVPENDING = -1;
    const STATUS_CLOSED = 0;
    const STATUS_OPEN = 1;
    const STATUS_DEPRECATED = 2;

    public $username;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Traveler the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'traveler';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, userId, revision, status, componentId', 'required'),
            array('userId, revision, status, parentId, componentId, rootId', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('createTime, modification, updateTime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, userId,rootId, createTime, updateTime, revision, status, parentId, componentId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'component' => array(self::BELONGS_TO, 'Components', 'componentId'),
            'project' => array(self::BELONGS_TO,'Project',array('projectId' => 'id'),'through'=>'component'),//join?
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'equipments' => array(self::MANY_MANY, 'Equipment', 'issue(equipmentId, travelerId)'),
            'steps' => array(self::HAS_MANY, 'Step', 'travelerId', 'order' => 'position'),
            'stepCount' => array(self::STAT, 'Step', 'travelerId'),
            'parent' => array(self::BELONGS_TO, 'Traveler', 'parentId'),
            'travelers' => array(self::HAS_MANY, 'Traveler', 'parentId', 'order' => 'revision DESC'),
            'travelerCount' => array(self::STAT, 'Traveler', 'parentId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'userId' => Yii::t('default', 'User'),
            'projectId' => Yii::t('default', 'Project'),
            'componentId' => Yii::t('default', 'Component'),
            'createTime' => Yii::t('default', 'Create Time'),
            'updateTime' => Yii::t('default', 'Update Time'),
            'revision' => Yii::t('default', 'Revision'),
            'status' => Yii::t('default', 'Status'),
            'username' => Yii::t('default', 'Username'),
            'name' => Yii::t('default','Name'),
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('userId', $this->userId, true);
        $criteria->compare('revision', $this->revision, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('componentId', $this->componentId, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getNextRevision() {
        if ($this->parentId) {
            return $this->parent->travelerCount + 1;
        } else {
            return 1;
        }
    }

    /**
     * Prepares create_user_id and update_user_id attributes before
      saving.
     */
    protected function beforeSave() {
        if($this->isNewRecord)$this->userId = Yii::app()->user->id;
        return parent::beforeSave();
    }

    protected function beforeDelete() {
        $steps = Step::model()->getStepParent($this->id);
        foreach ($steps as $step) {
            $step->delete();
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
                'updateAttribute' => 'updateTime',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    /**
     * Retrieves a list of status
     * @return array an array of available status.
     */
    public function getStatusOptions() {
        return array(
            self::STATUT_REVPENDING => " Rev " . Yii::t('default', 'Pending'),
            self::STATUS_CLOSED => Yii::t('default', 'Published'),
            self::STATUS_OPEN => Yii::t('default', 'Unpublished'),
            self::STATUS_DEPRECATED => Yii::t('default', 'Deprecated'),
        );
    }

    /**
     * @return string the status text display for the current step
     */
    public function getStatusText() {
        $statusOptions = $this->statusOptions;
        return isset($statusOptions[$this->status]) ?
                $statusOptions[$this->status] : "unknown status ({$this->status})";
    }
    
    public function getCanReorder(){
        if($this->stepCount < 2) return false;
        if($this->status != 1) return false;
        if(Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $this->userId) return true;
        return false;
    }

    public function addStep($step) {
        $step->travelerId = $this->id;
        return $step->save();
    }

    public function getStepParent() {
        return Step::model()->getStepParent($this->id);
    }

    public function getNumberDiscrepancies($issueId = null) {
        if (is_null($issueId)) {
            $nbDiscrepancies = 0;
            foreach ($this->steps as $value) {
                if ($value->discrepancyId) {
                    if (is_null($value->discrepancy->issueId))
                        $nbDiscrepancies++;
                }
            }
            return $nbDiscrepancies;
        }else {
            $result = Nonconformity::model()->findAll("issueId = $issueId");
            return count($result);
        }
    }

    public function getNumberComments($issueId = null) {
        if (is_null($issueId)) {
            $nbComments = 0;
            foreach ($this->steps as $value) {
                $nbComments += $value->countComment();
            }
            return $nbComments;
        } else {
            $result = Comment::model()->findAll("issueId = $issueId");
            return count($result);
        }
    }

    public function getPreviousTraveler() {
        if ($this->parentId) {
            $traveler = Traveler::model()->find("parentId = $this->parentId AND revision = $this->revision - 1");
            if ($traveler) {
                return $traveler;
            } else {
                return Traveler::model()->findByPk($this->parentId);
            }
        } else {
            return false;
        }
    }

    public function getLastRevision() {
        if ($this->parentId)
            $id = $this->parentId;
        else {
            $id = $this->id;
        }
        $criteria = new CDbCriteria;
        $criteria->select = 'MAX(revision) as revision';
        $criteria->condition = "parentId = $id";
        $lastRev = Traveler::model()->find($criteria);
        return (int) $lastRev->revision;
    }

    public function getConcatened() {
        if ($this->status == -1) {
            $pend = "| Rev (" . ($this->revision + 1) . ") " . Yii::t('default', "Pending") . " (" . $this->user->username . ")";
        } else {
            $pend = "";
        }
        return $this->projectName . " - " . $this->componentName . " - " . $this->name . " (v$this->revision)  $pend";
    }

    public function getProgress($issueId) {
        if (is_null($issueId))
            return null;
        $count = 0;
        $result = 0;
        foreach ($this->getStepParent() as $parent) {
            $progress = $parent->getProgress($issueId);
            if (!is_null($progress)) {
                $count++;
                $result += $progress;
            }
        }
        if ($count == 0)
            return null;
        return $result / $count;
    }

    public function getComponentName() {
        if ($this->component)
            return $this->component->name;
        return "";
    }

    public function getProjectName() {
        if ($this->component)
            return $this->component->project->name;
        return "";
    }

    public function getTitle() {
        return $this->projectName . " - " . $this->componentName . " - " . $this->name;
    }

    public function getIdentifier() {
        return trim($this->component->project->identifier) . "-" . trim($this->component->identifier) . "-" . $this->id;
    }

    public function getDateCreated() {
        // Format dates based on the locale
        return Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat, $this->createTime);
    }
    
    public  function getStepImport(){
        $projectId = $this->component->projectId;
        $sql = "SELECT t.name AS grouping, s.id, s.name AS text
                    FROM traveler AS t INNER JOIN
                            step AS s ON s.travelerId = t.id
                            INNER JOIN components  AS c ON c.id = t.componentId
                            WHERE s.parentId IS NULL and t.id <> $this->id AND t.status = 0";
        return Yii::app()->db->createCommand($sql)->queryAll(); //
    }
	 public  function getStepImportForCreators(){
        $projectId = $this->component->projectId;
        $sql = "SELECT t.name AS grouping, s.id, s.name AS text
                    FROM traveler AS t INNER JOIN
                            step AS s ON s.travelerId = t.id
                            INNER JOIN components  AS c ON c.id = t.componentId
                            WHERE s.parentId IS NULL and t.id <> $this->id";
        return Yii::app()->db->createCommand($sql)->queryAll(); //AND t.status = 0
    }
    public  function getStepDuplicate(){
        $sql = "SELECT s.id, s.position, s.name
                    FROM step AS s 
                    WHERE s.travelerId = $this->id AND parentId IS NULL ORDER BY position";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    public  function getTravelerImport(){
        $projectId = $this->component->projectId;
        $sql = "SELECT t.name AS text
                    FROM traveler AS t ";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    public  function getTravelerForTree(){
        $sql = "SELECT CONCAT(p.identifier , ' ' , p.name) as pname, p.id as pid, CONCAT(c.identifier , ' ' , c.name) as cname, c.id as cid, t.name as tname, t.id as tid, t.revision, t.status
                    FROM project AS p 
                    INNER JOIN components AS c ON c.projectId = p.id 
                    INNER JOIN traveler  AS t ON c.id = t.componentId
                    ORDER BY p.position,c.position,t.rootId,t.revision DESC";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    public function getNode($workId){
        if(!$this->parentId){
            if(!$this->travelers){
                return true;
            }else{
                return false;
            }
        }else{
            return $workId != $this->parentId;
        }
    }
    


    public function isDeletable(){
        if($this->equipments) return false;
        if($this->travelers) return false;
        if(Yii::app()->user->getState('role') == 4 && ($this->status == 1 OR $this->status == 0)) return true;
        return $this->status == 1 && Yii::app()->user->id == $this->userId;
    }
	
	public static function findMyTravelers() {
		$userId=Yii::app()->user->id;
            return self::model()->findAll(array(
                        'condition' => "userId = $userId ",
                        'order' => 'updateTime DESC',
						'limit' => 10,
						
                        
            ));//'limit' => $limit,
    }
}