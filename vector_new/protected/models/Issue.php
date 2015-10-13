<?php

/**
 * This is the model class for table "issue".
 *
 * The followings are the available columns in table 'issue':
 * @property integer $id
 * @property integer $travelerId
 * @property integer $equipmentId
 * @property integer $userId
 * @property string $createTime
 * @property string $closedTime
 * @property boolean $status
 *
 * The followings are the available model relations:
 * @property Equipment $equipment
 * @property Traveler $traveler
 * @property Comment[] $comments
 * @property Discrepancy[] &discrepancies
 * @property Result[] $results
 */
class Issue extends CActiveRecord
{
    const STATUS_CLOSED = 0;
    const STATUS_OPEN = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'issue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('travelerId, equipmentId, status', 'required'),
			array('travelerId, equipmentId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
                        array('createTime, closedTime', 'safe'),
			// @todo Please remove those attributes that should not be searched.
			array('id, travelerId,createTime, closedTime, equipmentId, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'equipment' => array(self::BELONGS_TO, 'Equipment', 'equipmentId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'traveler' => array(self::BELONGS_TO, 'Traveler', 'travelerId'),
                        'comments' => array(self::HAS_MANY, 'Comment', 'issueId'),
                        'discrepancies' => array(self::HAS_MANY, 'Discrepancy', 'issueId'),
                        'results' => array(self::HAS_MANY, 'Result', 'issueId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'travelerId' => Yii::t('default','Traveler'),
			'equipmentId' => Yii::t('default','Equipment'),
			'status' => Yii::t('default','Status'),
			'createTime' => Yii::t('default','Create Time'),
			'closedTime' => Yii::t('default','Closed Time'),
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('travelerId',$this->travelerId);
		$criteria->compare('equipmentId',$this->equipmentId);
                $criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('closedTime',$this->closedTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Issue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
    
    public function getIssueByPkFk($travelerId, $equipmentId){
        $issue = Issue::model()->find("travelerId = $travelerId AND equipmentId = $equipmentId");
        if($issue){
            return $issue;
        }  else {
            return null;
        }
    }
    
    public function getProgress(){
        $count = 0;
        $result = 0;
        foreach ($this->traveler->getStepParent() as $parent){
            $progress = $parent->getProgress($this->id);
            if(!is_null($progress)){
                $count++;
                $result += $progress;
            }
        }
        if($count == 0)return null;
        return $result / $count;
    }
    
    public function getStatusOptions() {
        return array(
            self::STATUS_CLOSED => Yii::t('default','Published'),
            self::STATUS_OPEN => Yii::t('default','Unpublished'),
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
    
    
    public function complete(){
        return $this->getProgress() == 100;
    }
    
    public function getProgressText(){
        return round($this->getProgress())."%";
    }
    
    public function getDateCreated() {
        // Format dates based on the locale
        return Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat,$this->createTime);
    }
    
    public function close() {
        $this->status = 0;
        $this->closedTime =new CDbExpression('NOW()');
        $this->save();
    }


    public function behaviors(){
    return array( 'CAdvancedArFindBehavior' => array(
        'class' => 'application.extensions.CAdvancedArFindBehavior')); 
    }
    
    protected function beforeDelete() {
        foreach (Nonconformity::model()->findAll("issueId = $this->id") as $discrepancy){
            $discrepancy->delete();
        }
        foreach (Comment::model()->findAll("issueId = $this->id") as $comment){
            $comment->delete();
        }
        foreach (Result::model()->findAll("issueId = $this->id") as $result){
            $result->delete();   
        }
        return parent::beforeDelete();
    }
	public static function findMyIssues() {
		$userId=Yii::app()->user->id;
            return self::model()->findAll(array(
                        'condition' => "userId = $userId ",
                        'order' => 'createTime DESC',
						'limit' => 10,
						
                        
            ));
	}
}
