<?php

/**
 * This is the model class for table "discrepancy".
 *
 * The followings are the available columns in table 'discrepancy':
 * @property integer $id
 * @property string $discrepancyDescription
 * @property integer $discrepancyDescriptionBy
 * @property string $discrepancyDescriptionDate
 * @property string $causeOfNonconformance
 * @property integer $causeOfNonconformanceBy
 * @property string $causeOfNonconformanceDate
 * @property string $disposition
 * @property integer $dispositionBy
 * @property string $dispositionDate
 * @property string $dispositionVerifyNote
 * @property integer $dispositionVerifyNoteBy
 * @property string $dispositionVerifyNoteDate
 * @property string $correctiveActionToPreventRecurrence
 * @property integer $correctiveActionToPreventRecurrenceBy
 * @property string $correctiveActionToPreventRecurrenceDate
 * @property string $correctiveActionVerifyNote
 * @property integer $correctiveActionVerifyNoteBy
 * @property string $correctiveActionVerifyNoteDate
 * @property integer $identifiedProblemArea
 * @property string $closeoutNote
 * @property integer $reviewedBy
 * @property string $reviewedDate
 * @property integer $stepId
 * @property integer $issueId
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Step $step
 * @property File $files
 * @property User $causeOfNonconformanceBy0
 * @property User $correctiveActionToPreventRecurrenceBy0
 * @property User $discrepancyDescriptionBy0
 * @property User $dispositionBy0
 * @property User $dispositionVerifyNoteBy0
 * @property User $reviewedBy0
 * @property User $correctiveActionVerifyNoteBy0
 */
class Discrepancy extends CActiveRecord {
    
    const STATUS_CLOSED = 0;
    const STATUS_OPEN = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Discrepancy the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'discrepancy';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('stepId, discrepancyDescription', 'required'),
            array('closeoutNote', 'required', 'on'=>'closeout'),
            array('discrepancyDescriptionBy, issueId, causeOfNonconformanceBy, dispositionBy, dispositionVerifyNoteBy, correctiveActionToPreventRecurrenceBy, correctiveActionVerifyNoteBy, identifiedProblemArea, reviewedBy, stepId, status', 'numerical', 'integerOnly' => true),
            array('discrepancyDescription, discrepancyDescriptionDate, causeOfNonconformance, causeOfNonconformanceDate, disposition, dispositionDate, dispositionVerifyNote, dispositionVerifyNoteDate, correctiveActionToPreventRecurrence, correctiveActionToPreventRecurrenceDate, correctiveActionVerifyNote, correctiveActionVerifyNoteDate, closeoutNote, reviewedDate', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, discrepancyDescription, discrepancyDescriptionBy, discrepancyDescriptionDate, causeOfNonconformance, causeOfNonconformanceBy, causeOfNonconformanceDate, disposition, dispositionBy, dispositionDate, dispositionVerifyNote, dispositionVerifyNoteBy, dispositionVerifyNoteDate, correctiveActionToPreventRecurrence, correctiveActionToPreventRecurrenceBy, correctiveActionToPreventRecurrenceDate, correctiveActionVerifyNote, correctiveActionVerifyNoteBy, correctiveActionVerifyNoteDate, identifiedProblemArea, closeoutNote, reviewedBy, reviewedDate, stepId, status', 'safe', 'on' => 'search'),
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
            'causeOfNonconformanceBy0' => array(self::BELONGS_TO, 'User', 'causeOfNonconformanceBy'),
            'correctiveActionToPreventRecurrenceBy0' => array(self::BELONGS_TO, 'User', 'correctiveActionToPreventRecurrenceBy'),
            'discrepancyDescriptionBy0' => array(self::BELONGS_TO, 'User', 'discrepancyDescriptionBy'),
            'dispositionBy0' => array(self::BELONGS_TO, 'User', 'dispositionBy'),
            'dispositionVerifyNoteBy0' => array(self::BELONGS_TO, 'User', 'dispositionVerifyNoteBy'),
            'reviewedBy0' => array(self::BELONGS_TO, 'User', 'reviewedBy'),
            'correctiveActionVerifyNoteBy0' => array(self::BELONGS_TO, 'User', 'correctiveActionVerifyNoteBy'),
            'files' => array(self::HAS_MANY, 'File', 'discrepancyId'),
            'filesCount' => array(self::STAT,'File','discrepancyId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'discrepancyDescription' => Yii::t('default','Description'),
            'discrepancyDescriptionBy' => Yii::t('default','Description By'),
            'discrepancyDescriptionDate' => Yii::t('default','Discrepancy Description Date'),
            'causeOfNonconformance' => Yii::t('default','Cause Of Nonconformance'),
            'causeOfNonconformanceBy' => Yii::t('default','Cause Of Nonconformance By'),
            'causeOfNonconformanceDate' => Yii::t('default','Cause Of Nonconformance Date'),
            'disposition' => Yii::t('default','Disposition'),
            'dispositionBy' => Yii::t('default','Disposition By'),
            'dispositionDate' => Yii::t('default','Disposition Date'),
            'dispositionVerifyNote' => Yii::t('default','Disposition Verify Note'),
            'dispositionVerifyNoteBy' => Yii::t('default','Disposition Verify Note By'),
            'dispositionVerifyNoteDate' => Yii::t('default','Disposition Verify Note Date'),
            'correctiveActionToPreventRecurrence' => Yii::t('default','Corrective Action To Prevent Recurrence'),
            'correctiveActionToPreventRecurrenceBy' => Yii::t('default','Corrective Action To Prevent Recurrence By'),
            'correctiveActionToPreventRecurrenceDate' => Yii::t('default','Corrective Action To Prevent Recurrence Date'),
            'correctiveActionVerifyNote' => Yii::t('default','Corrective Action Verify Note'),
            'correctiveActionVerifyNoteBy' => Yii::t('default','Corrective Action Verify Note By'),
            'correctiveActionVerifyNoteDate' => Yii::t('default','Corrective Action Verify Note Date'),
            'identifiedProblemArea' => Yii::t('default','Identified Problem Area'),
            'closeoutNote' => Yii::t('default','Closeout Note'),
            'reviewedBy' => Yii::t('default','Reviewed By'),
            'reviewedDate' => Yii::t('default','Reviewed Date'),
            'stepId' => Yii::t('default','Step'),
            'issueId' => Yii::t('default','Equiment'),
            'status' => Yii::t('default','Status'),
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
        $criteria->compare('discrepancyDescription', $this->discrepancyDescription, true);
        $criteria->compare('discrepancyDescriptionBy', $this->discrepancyDescriptionBy);
        $criteria->compare('discrepancyDescriptionDate', $this->discrepancyDescriptionDate, true);
        $criteria->compare('causeOfNonconformance', $this->causeOfNonconformance, true);
        $criteria->compare('causeOfNonconformanceBy', $this->causeOfNonconformanceBy);
        $criteria->compare('causeOfNonconformanceDate', $this->causeOfNonconformanceDate, true);
        $criteria->compare('disposition', $this->disposition, true);
        $criteria->compare('dispositionBy', $this->dispositionBy);
        $criteria->compare('dispositionDate', $this->dispositionDate, true);
        $criteria->compare('dispositionVerifyNote', $this->dispositionVerifyNote, true);
        $criteria->compare('dispositionVerifyNoteBy', $this->dispositionVerifyNoteBy);
        $criteria->compare('dispositionVerifyNoteDate', $this->dispositionVerifyNoteDate, true);
        $criteria->compare('correctiveActionToPreventRecurrence', $this->correctiveActionToPreventRecurrence, true);
        $criteria->compare('correctiveActionToPreventRecurrenceBy', $this->correctiveActionToPreventRecurrenceBy);
        $criteria->compare('correctiveActionToPreventRecurrenceDate', $this->correctiveActionToPreventRecurrenceDate, true);
        $criteria->compare('correctiveActionVerifyNote', $this->correctiveActionVerifyNote, true);
        $criteria->compare('correctiveActionVerifyNoteBy', $this->correctiveActionVerifyNoteBy);
        $criteria->compare('correctiveActionVerifyNoteDate', $this->correctiveActionVerifyNoteDate, true);
        $criteria->compare('identifiedProblemArea', $this->identifiedProblemArea);
        $criteria->compare('closeoutNote', $this->closeoutNote, true);
        $criteria->compare('reviewedBy', $this->reviewedBy);
        $criteria->compare('reviewedDate', $this->reviewedDate, true);
        $criteria->compare('stepId', $this->stepId, true);
        $criteria->compare('issueId', $this->issueId, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    const AREA_MATERIAL = 0;
    const AREA_MANPOWER = 1;
    const AREA_METHOD = 2;
    const AREA_MACHINE = 3;
    const AREA_MEASUREMENT = 4;

    /**
     * Retrieves a list of status
     * @return array an array of available status.
     */
    public function getAreaOptions() {
        return array(
            self::AREA_MATERIAL => Yii::t('default','Material'),
            self::AREA_MANPOWER => Yii::t('default','Manpower'),
            self::AREA_METHOD => Yii::t('default','Method'),
            self::AREA_MACHINE => Yii::t('default','Machine'),
            self::AREA_MEASUREMENT => Yii::t('default','Measurement'),
        );
    }

    /**
     * @return string the status text display for the current step
     */
    public function getAreaText() {
        $areaOptions = $this->areaOptions;
        return isset($areaOptions[$this->identifiedProblemArea]) ?
                $areaOptions[$this->identifiedProblemArea] : "unknown status ({$this->identifiedProblemArea})";
    }

    /**
     * Prepares create_user_id and update_user_id attributes before
      saving.
     */
    protected function beforeDelete() {
        foreach ($this->files as $file){
            $file->delete();
        }
        return parent::beforeDelete();
    }
    
    public function beforeSave() {
     
        if ($this->causeOfNonconformance != "") {
            $this->causeOfNonconformanceDate = new CDbExpression('GETDATE()');
            $this->causeOfNonconformanceBy = Yii::app()->user->id;
        } else {
            $this->causeOfNonconformanceDate = null;
            $this->causeOfNonconformanceBy = null;
        }
        if ($this->correctiveActionToPreventRecurrence != "") {
            $this->correctiveActionToPreventRecurrenceDate = new CDbExpression('GETDATE()');
            $this->correctiveActionToPreventRecurrenceBy = Yii::app()->user->id;
        } else {
            $this->correctiveActionToPreventRecurrenceDate = null;
            $this->correctiveActionToPreventRecurrenceBy = null;
        }
        if ($this->correctiveActionVerifyNote != "") {
            $this->correctiveActionVerifyNoteDate = new CDbExpression('GETDATE()');
            $this->correctiveActionVerifyNoteBy = Yii::app()->user->id;
        } else {
            $this->correctiveActionVerifyNoteDate = null;
            $this->correctiveActionVerifyNoteBy = null;
        }
        if ($this->discrepancyDescription != "") {
            $this->discrepancyDescriptionDate = new CDbExpression('GETDATE()');
            $this->discrepancyDescriptionBy = Yii::app()->user->id;
        } else {
            $this->discrepancyDescriptionDate = null;
            $this->discrepancyDescriptionBy = null;
        }
        if ($this->disposition != "") {
            $this->dispositionDate = new CDbExpression('GETDATE()');
            $this->dispositionBy = Yii::app()->user->id;
        } else {
            $this->dispositionDate = null;
            $this->dispositionBy = null;
        }
        if ($this->dispositionVerifyNote != "") {
            $this->dispositionVerifyNoteDate = new CDbExpression('GETDATE()');
            $this->dispositionVerifyNoteBy = Yii::app()->user->id;
        } else {
            $this->dispositionVerifyNoteDate = null;
            $this->dispositionVerifyNoteBy = null;
        }
        if ($this->closeoutNote != "") {
            $this->reviewedDate = new CDbExpression('GETDATE()');
            $this->reviewedBy = Yii::app()->user->id;
        } else {
            $this->reviewedDate = null;
            $this->reviewedBy = null;
        }

        return parent::beforeSave();
    }
    
    public function getVisible(){
        return !Yii::app()->user->isGuest && $this->status == 1;
    }
    
    public function getReviewer(){
        if($this->reviewedBy){
            return $this->reviewedBy0->username;
        }else{
            return Yii::t('default','Not Close');
        }
    }
    
    public static function findOpenDiscrepancies($limit = 100) {
//get all comments across all projects
            return self::model()->findAll(array(
                        'condition' => 'status = 1',
                        'order' => 'discrepancyDescriptionDate DESC',
                        'limit' => $limit,
            ));
    }
    
    public function getTimeElapsed() {
        $etime = time() - strtotime($this->discrepancyDescriptionDate);

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
    
    public function getStatusOptions() {
        return array(
            self::STATUS_CLOSED => Yii::t('default', 'Close'),
            self::STATUS_OPEN => Yii::t('default', 'Open'),
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
    
    public function getEquipment(){
        return Issue::model()->find("id = $this->issueId")->equipment;
    }
    
    
    public function getListEquipment(){
        return Yii::app()->db->createCommand('select i.id, e.identifier from discrepancy d JOIN issue i ON i.id = d.issueId JOIN equipment e ON e.id = i.equipmentId')->queryAll();    
    }
    
    public function getVisibleDelete(){
        return $this->discrepancyDescriptionBy == Yii::app()->user->id && $this->status == 1;
    }
    
    public function getVisibleClose(){
        return Yii::app()->user->getState('role') > 1 && $this->status == 1;
    }
    

}