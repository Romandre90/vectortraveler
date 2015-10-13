<?php

/**
 * This is the model class for table "nonconformity".
 *
 * The followings are the available columns in table 'nonconformity':
 * @property integer $id
 * @property string $description
 * @property int $descriptionById
 * @property string $descriptionTime
 * @property integer $originatorId
 * @property string $createTime
 * @property integer $activity
 * @property string $activityOther
 * @property integer $activityById
 * @property string $activityTime
 * @property int $area
 * @property integer $areaById
 * @property string $edms
 * @property string $areaTime
 * @property integer $importance
 * @property string $importanceTime
 * @property integer $importanceById
 * @property integer $disposition
 * @property integer $dispositionById
 * @property string $dispositionTime
 * @property string $dispositionDescription
 * @property string $corrective
 * @property string $correctiveTime
 * @property integer $correctiveById
 * @property integer $closedById
 * @property string $closedTime
 * @property string $closure
 * @property boolean $status
 * @property integer $issueId
 * @property integer $stepId
 *
 * The followings are the available model relations:
 * @property User $areaBy
 * @property User $activityBy
 * @property User $descriptionBy
 * @property User $closedBy
 * @property User $correctiveBy
 * @property User $dispositionBy
 * @property User $importanceBy
 * @property User $originator
 * @property Issue $issue
 * @property Step $step
 * @property Equipment $equipment
 * @property Traveler $traveler
 * @property File[] $files
 */
class Nonconformity extends CActiveRecord {

    const ACTIVITY_INCOMING = 1;
    const ACTIVITY_INPROCESS = 2;
    const ACTIVITY_FINAL = 3;
    const ACTIVITY_OTHER = 4;
    
    const IMPORTANCE_NON_CRITICAL = 1;
    const IMPORTANCE_CRITICAL = 2;
    
    const AREA_MATERIAL = 1;
    const AREA_MANPOWER = 2;
    const AREA_METHOD = 3;
    const AREA_MACHINE = 4;
    const AREA_MEASUREMENT = 5;
    
    const DISPOSITION_USE_AS_IS = 1;
    const DISPOSITION_REPAIR = 2;
    const DISPOSITION_REJECT = 3;
    const DISPOSITION_REWORK = 4;
    const DISPOSITION_RETURN_TO_SUPPLIER = 5;
    
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'nonconformity';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('description, originatorId, createTime, status, issueId, stepId', 'required'),
            array('activityOther','required','on'=>'other_inspection'),
            array('closure', 'required', 'on'=>'closeout'),
            array('originatorId, descriptionById, activity, activityById, areaById, importance, importanceById, disposition, dispositionById, correctiveById, closedById, issueId, stepId', 'numerical', 'integerOnly' => true),
            array('activityOther, edms', 'length', 'max' => 50),
            array('area, descriptionTime, areaTime, activityTime, importanceTime, dispositionTime, dispositionDescription, corrective, correctiveTime, closedTime, closure', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, description, edms, descriptionTime, descriptionById, originatorId, createTime, activity,activityTime, activityOther, activityById, area, areaById, areaTime, importance, importanceTime, importanceById, disposition, dispositionById, dispositionTime, dispositionDescription, corrective, correctiveTime, correctiveById, closedById, closedTime, closure, status, issueId, stepId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'areaBy' => array(self::BELONGS_TO, 'User', 'areaById'),
            'activityBy' => array(self::BELONGS_TO, 'User', 'activityById'),
            'closedBy' => array(self::BELONGS_TO, 'User', 'closedById'),
            'correctiveBy' => array(self::BELONGS_TO, 'User', 'correctiveById'),
            'descriptionBy' => array(self::BELONGS_TO, 'User', 'descriptionById'),
            'dispositionBy' => array(self::BELONGS_TO, 'User', 'dispositionById'),
            'importanceBy' => array(self::BELONGS_TO, 'User', 'importanceById'),
            'originator' => array(self::BELONGS_TO, 'User', 'originatorId'),
            'step' => array(self::BELONGS_TO, 'Step', 'stepId'),
            'files' => array(self::HAS_MANY, 'File', 'discrepancyId'),
            'filesCount' => array(self::STAT, 'File', 'discrepancyId'),
            'issue' => array(self::BELONGS_TO, 'Issue', array('issueId' => 'id')),
            'equipment' => array(self::BELONGS_TO, 'Equipment', array('equipmentId' => 'id'), 'through' => 'issue'),
            'traveler' => array(self::BELONGS_TO, 'Traveler', array('travelerId' => 'id'), 'through' => 'issue'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'description' => Yii::t('default','Description of nonconformity'),
            'descriptionById' => Yii::t('default','Description By'),
            'descriptionTime' => Yii::t('default','Description Time'),
            'originatorId' => Yii::t('default','Originator'),
            'createTime' => Yii::t('default','Create Time'),
            'activity' => Yii::t('default','Found during what activity'),
            'activityOther' => Yii::t('default','Activity Other'),
            'activityById' => Yii::t('default','Activity By'),
            'activityTime' => Yii::t('default','Activity Time'),
            'area' => Yii::t('default','Identified Problem Area'),
            'areaById' => Yii::t('default','Area By'),
            'areaTime' => Yii::t('default','Area Time'),
            'importance' => Yii::t('default','Importance'),
            'importanceTime' => Yii::t('default','Importance Time'),
            'importanceById' => Yii::t('default','Importance By'),
            'disposition' => Yii::t('default','Disposition'),
            'dispositionById' => Yii::t('default','Disposition By'),
            'dispositionTime' => Yii::t('default','Disposition Time'),
            'dispositionDescription' => Yii::t('default','Description of disposition action'),
            'corrective' => Yii::t('default','Corrective/Preventive action'),
            'correctiveTime' => Yii::t('default','Corrective Time'),
            'correctiveById' => Yii::t('default','Corrective By'),
            'closedById' => Yii::t('default','Closed By'),
            'closedTime' => Yii::t('default','Closed Time'),
            'closure' => Yii::t('default','Closure'),
            'status' => Yii::t('default','Status'),
            'issueId' => Yii::t('default','Issue'),
            'stepId' => Yii::t('default','Step'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('descriptionById', $this->descriptionById);
        $criteria->compare('descriptionTime', $this->descriptionTime, true);
        $criteria->compare('originatorId', $this->originatorId);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('activity', $this->activity);
        $criteria->compare('activityOther', $this->activityOther, true);
        $criteria->compare('activityById', $this->activityById);
        $criteria->compare('activityTime', $this->activityTime, true);
        $criteria->compare('area', $this->area, true);
        $criteria->compare('areaById', $this->areaById);
        $criteria->compare('areaTime', $this->areaTime, true);
        $criteria->compare('importance', $this->importance);
        $criteria->compare('importanceTime', $this->importanceTime, true);
        $criteria->compare('importanceById', $this->importanceById);
        $criteria->compare('disposition', $this->disposition);
        $criteria->compare('dispositionById', $this->dispositionById);
        $criteria->compare('dispositionTime', $this->dispositionTime, true);
        $criteria->compare('dispositionDescription', $this->dispositionDescription, true);
        $criteria->compare('corrective', $this->corrective, true);
        $criteria->compare('correctiveTime', $this->correctiveTime, true);
        $criteria->compare('correctiveById', $this->correctiveById);
        $criteria->compare('closedById', $this->closedById);
        $criteria->compare('closedTime', $this->closedTime, true);
        $criteria->compare('closure', $this->closure, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('issueId', $this->issueId);
        $criteria->compare('stepId', $this->stepId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Nonconformity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getActivityOptions() {
        return array(
            self::ACTIVITY_INCOMING => Yii::t('default', 'Incomming inspection'),
            self::ACTIVITY_INPROCESS => Yii::t('default', 'In-process inspection'),
            self::ACTIVITY_FINAL => Yii::t('default', 'Final inspection'),
            self::ACTIVITY_OTHER => Yii::t('default', 'Other'),
        );
    }

    /**
     * @return string the role text display for the current user
     */
    public function getActivityText() {
        $roleOptions = $this->roleOptions;
        return isset($roleOptions[$this->activity]) ?
                $roleOptions[$this->activity] : "unknown status ({$this->activity})";
    }
    
    
    public function getImportanceOptions() {
        return array(
            self::IMPORTANCE_NON_CRITICAL => Yii::t('default', 'Non critical'),
            self::IMPORTANCE_CRITICAL => Yii::t('default', 'Critical'),
        );
    }
    
    public function getVisible(){
        return !Yii::app()->user->isGuest && $this->status == 1;
    }

    /**
     * @return string the role text display for the current user
     */
    public function getImportanceText() {
        $roleOptions = $this->importanceOptions;
        return isset($roleOptions[$this->importance]) ?
                $roleOptions[$this->importance] : "N/A";
    }
    public function getDispositionOptions() {
        return array(
            self::DISPOSITION_USE_AS_IS => Yii::t('default', 'Use-as-is'),
            self::DISPOSITION_REPAIR => Yii::t('default', 'Repair'),
            self::DISPOSITION_REJECT => Yii::t('default', 'Reject'),
            self::DISPOSITION_REWORK => Yii::t('default', 'Rework'),
            self::DISPOSITION_RETURN_TO_SUPPLIER => Yii::t('default', 'Return to supplier'),
        );
    }
    
    public function getAreaOptions() {
        return array(
            self::AREA_MATERIAL => Yii::t('default', 'Material'),
            self::AREA_MANPOWER => Yii::t('default', 'Manpower'),
            self::AREA_METHOD => Yii::t('default', 'Method'),
            self::AREA_MACHINE => Yii::t('default', 'Machine'),
            self::AREA_MEASUREMENT => Yii::t('default', 'Measurement'),
        );
    }

    /**
     * @return string the role text display for the current user
     */
    public function getDispositionText() {
        $roleOptions = $this->dispositionOptions;
        return isset($roleOptions[$this->disposition]) ?
                $roleOptions[$this->disposition] : "unknown status ({$this->disposition})";
    }
     public function getAreaText() {
        $roleOptions = $this->areaOptions;
        return isset($roleOptions[$this->area]) ?
                $roleOptions[$this->area] : "unknown status ({$this->area})";
    }
    
    public function getStatusOptions() {
        return array(
            self::STATUS_CLOSE => Yii::t('default', 'Close'),
            self::STATUS_OPEN => Yii::t('default', 'Open'),
        );
    }
    
    public static function findOpenDiscrepancies($limit = 100) {
//get all comments across all projects
            return self::model()->findAll(array(
                        'condition' => 'status = 1',
                        'order' => 'createTime DESC',
                        'limit' => $limit,
            ));
    }
    
    public function getTimeElapsed() {
        $etime = time() - strtotime($this->createTime);

        if ($etime < 1) {
            return Yii::t('default','now');
        }

        $a = array( 31536000   =>  Yii::t('default','year'),
                    2592000        =>  Yii::t('default','month'),
                    604800        =>  Yii::t('default','week'),
                    86400             =>  Yii::t('default','day'),
                    3600                  =>  Yii::t('default','hour'),
                    60                      =>  Yii::t('default','minute'),
                    1                       =>  Yii::t('default','second')
                    );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                if($str == "mois") return $r . ' ' . $str;
                return $r . ' ' . $str . ($r > 1 ? 's' : '');
            }
        }
    }
    
    /**
     * @return string the role text display for the current user
     */
    public function getStatusText() {
        $roleOptions = $this->statusOptions;
        return isset($roleOptions[$this->status]) ?
                $roleOptions[$this->status] : "unknown status ({$this->status})";
    }
    
        public function getDateCreated() {
        // Format dates based on the locale
        return $this->getDateFormat($this->createTime);
    }
    
    public function getDateFormat($date){
         return Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat, $date);
    }
    
    public  function beforeSave(){
        if($this->isNewRecord){
            $this->originatorId = Yii::app()->user->id;
        }
            
        $date = new CDbExpression('GETDATE()');
        if($this->corrective == ''){
            $this->correctiveById = null;
            $this->correctiveTime = null;
        }elseif(!$this->correctiveBy){
              $this->correctiveById = $userId;
              $this->correctiveTime = $date;
        } 
        if($this->activity != 4){
            $this->activityOther = null;
        }
        return parent::beforeSave();
    }
    
    protected function beforeDelete() {
        foreach ($this->files as $file){
            $file->delete();
        }
        return parent::beforeDelete();
    }
    
    public function getVisibleDelete(){
        return $this->originatorId == Yii::app()->user->id && $this->status == 1;
    }
    
    public function getVisibleClose(){
        return Yii::app()->user->getState('role') > 1 && $this->status == 1;
    }
    
    public function getTravelerStep(){
        if(!$this->step)return null;
        if($this->step->parentId){
            $step = Yii::t('default','Step')." ".$this->step->parent->position.".".$this->step->position;
        }else{
            $step = Yii::t('default','Step')." ".$this->step->position.".0";
        }
        return $this->concatenateIdentity." ".$this->traveler->name." - ".$step;
    }
    
    public function getTravelerStepList(){
        $step = Yii::t('default','Step');
        $sql = "SELECT p.identifier [group],CONCAT(c.identifier,e.identifier,' ',t.name,' - $step ',CASE WHEN s.parentId IS NULL THEN CONCAT(s.position,'.0') ELSE CONCAT((SELECT position FROM step WHERE id = s.parentId),'.',s.position) END) travelerStep, nc.id FROM nonconformity nc
            JOIN issue i ON nc.issueId = i.id
            JOIN traveler t ON t.id = i.travelerId
            JOIN equipment e ON e.id = i.equipmentId
            JOIN components c ON c.id = e.componentId
            JOIN project p ON p.id = c.projectId
            JOIN step s ON s.id = nc.stepId";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    public function getUsers(){
        $sql = "SELECT originatorId, username FROM nonconformity JOIN
                            [user] as us ON originatorId = us.id";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public function getConcatenateIdentity(){
        if($this->equipment)
        return $this->equipment->getConcatenateIdentity();
        return null;
    }
    
    public function getConcatenateIdentityList(){
        $sql = "SELECT CONCAT(p.identifier,c.identifier,e.identifier) concatenateIdentity, nc.id FROM nonconformity nc
            JOIN issue i ON nc.issueId = i.id
            JOIN equipment e ON e.id = i.equipmentId
            JOIN components c ON c.id = e.componentId
            JOIN project p ON p.id = c.projectId";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
}
