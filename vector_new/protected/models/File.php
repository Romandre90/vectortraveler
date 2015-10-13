<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property integer $id
 * @property integer $discrepancyId
 * @property integer $stepId
 * @property string $createTime
 * @property integer $userId
 * @property string $fileSelected
 * @property string $link
 * @property string $name
 * @property string $description
 * @property integer $image
 * @property integer $copy
 *
 * The followings are the available model relations:
 * @property Nonconformity $discrepancy
 * @property Result $result Description
 * @property User $user
 */
class File extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return File the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'file';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('discrepancyId, stepId, userId', 'numerical', 'integerOnly' => true),
            array('fileSelected','required'),
            //array('fileSelected','file','maxSize'=>62914560,'tooLarge'=>'File has to be smaller than 60MB. If you need to upload bigger files please contact the administrator'),//if uncomment issue upload won't work
			array('fileSelected','safe'),
            array('description', 'length', 'max'=>255),
            array('name', 'length', 'max'=>25),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, discrepancyId, stepId, createTime, userId, fileSelected, link', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'discrepancy' => array(self::BELONGS_TO, 'Nonconformity', 'discrepancyId'),
            'step' => array(self::BELONGS_TO, 'Step', 'stepId'),
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'result' => array(self::HAS_ONE,'Result','fileId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'discrepancyId' => Yii::t('default', 'Nonconformity'),
            'stepId' => Yii::t('default', 'Step'),
            'createTime' => Yii::t('default', 'Create Time'),
            'userId' => Yii::t('default', 'User'),
            'fileSelected' => Yii::t('default', 'File Selected'),
            'link' => Yii::t('default', 'Link'),
            'name' => Yii::t('default', 'Name'),
            'description' => Yii::t('default', 'Description'),
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
        $criteria->compare('discrepancyId', $this->discrepancyId);
        $criteria->compare('stepId', $this->stepId);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('fileSelected', $this->fileSelected, true);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeDelete() {
        if(!$this->copy)@unlink(Yii::app()->params['dfs'] . "/$this->link");
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

    /**
     * Prepares create_user_id and update_user_id attributes before
      saving.
     */
    protected function beforeSave() {
        if($this->isNewRecord)$this->userId = Yii::app()->user->id;
        return parent::beforeSave();
    }
    
    
    public function copy($stepId){
        $this->id = null;
        $this->isNewRecord = true;
        $this->stepId = $stepId;
        $this->copy = 1;
        $this->save();
    }

    public function getDateCreated() {
        // Format dates based on the locale
        return Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat,$this->createTime);
    }
    
}