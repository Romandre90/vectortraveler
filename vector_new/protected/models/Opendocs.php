<?php


class Opendocs extends CActiveRecord{

	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Work the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'opendocs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('createTime','required'),
			array('issueId,userId', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('createTime, userId, issueId', 'safe', 'on'=>'search'),
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
			//'users' => array(self::HAS_MANY, 'Warnings', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issueId' => 'Issue',
			'createTime'=>'Opening Time',
			'userId'=>'User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	  protected function beforeSave() {
        if($this->isNewRecord)$this->userId = Yii::app()->user->id;
		//$date= new CDbExpression('GETDATE()');
		//$this->createTime=$date;
        return parent::beforeSave();
    }
  
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

/*		$criteria->compare('id',$this->id);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('value',$this->value);
		$criteria->compare('createTime',$this->value);
		$criteria->compare('expirationTime',$this->expirationTime);
		$criteria->compare('priority',$this->priority);*/


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
            public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'createTime',
				'updateAttribute' => 'createTime',

            ),
        );
    }


	
}