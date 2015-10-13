<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property string $text
 * @property string $createTime
 * @property string $updateTime
 * @property integer $userId
 * @property integer $stepId
 * @property integer $level
 * @property string $fileSelected
 * @property integer $issueId
 *
 * The followings are the available model relations:
 * @property Step $step
 * @property File $file
 * @property User $user
 * @property Issue $issue
 */
class Comment extends CActiveRecord {
    
    const LEVEL_INFO = 0;
    const LEVEL_WARN = 1;
    const LEVEL_ERROR = 2;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Comment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, stepId, level, issueId', 'numerical', 'integerOnly' => true),
            array('text,level', 'required'),
            array('text, createTime, updateTime', 'safe'),
            array('fileSelected', 'file', 'allowEmpty'=>true,'maxSize'=>1024*1024*55),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, text, level, issueId, createTime, updateTime, userId, stepId', 'safe', 'on' => 'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'issue' => array(self::BELONGS_TO, 'Equiment', 'issueId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'text' => Yii::t('default','Text'),
            'createTime' => Yii::t('default','Create Time'),
            'updateTime' => Yii::t('default','Update Time'),
            'level' => Yii::t('default','Level'),
            'fileSelected' => Yii::t('default','File Selected'),
            'userId' => Yii::t('default','User'),
            'issueId' => Yii::t('default','Equiment'),
            'stepId' => Yii::t('default','Step'),
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
        $criteria->compare('text', $this->text, true);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('updateTime', $this->updateTime, true);
        $criteria->compare('level', $this->level, true);
        $criteria->compare('issueId', $this->issueId, true);
        $criteria->compare('userId', $this->userId, true);
        $criteria->compare('stepId', $this->stepId, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Prepares create_user_id and update_user_id attributes before
      saving.
     */
    protected function beforeSave() {
        if($this->isNewRecord)$this->userId = Yii::app()->user->id;
        return parent::beforeSave();
    }
    
    /**
     * Prepares create_user_id and update_user_id attributes before
      saving.
     */
    protected function beforeDelete() {
        if($this->fileSelected){
            @unlink(Yii::app()->params['dfs'] . "/comment/$this->fileSelected");
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

    public static function findRecentComments($limit = 10, $travelerId = null) {
        if ($travelerId != null) {
            return self::model()->with(array(
            'traveler' => array('condition' => 'travelerId='.$travelerId)))->findAll(array(
                'order' => 'createTime DESC',
                'limit' => $limit,
            ));
        } else {
//get all comments across all projects
            return self::model()->findAll(array(
                        'order' => 'createTime DESC',
                        'limit' => $limit,
            ));
        }
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
     * Retrieves a list of status
     * @return array an array of available status.
     */
    public function getLevelOptions() {
        return array(
            self::LEVEL_INFO => Yii::t('default','Info'),
            self::LEVEL_WARN => Yii::t('default','Warning'),
            self::LEVEL_ERROR => Yii::t('default','Error'),
        );
    }

    /**
     * @return string the status text display for the current step
     */
    public function getLevelText() {
        $levelOptions = $this->levelOptions;
        return isset($levelOptions[$this->level]) ?
        $levelOptions[$this->level] : "unknown level ({$this->level})";
    }
    
    public function getEquipment(){
        return Issue::model()->find("id = $this->issueId")->equipment;
    }
}

