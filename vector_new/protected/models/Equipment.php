<?php

/**
 * This is the model class for table "equipment".
 *
 * The followings are the available columns in table 'equipment':
 * @property integer $id
 * @property integer $userId
 * @property integer $position
 * @property string $createTime
 * @property string $updateTime
 * @property string $identifier
 * @property string $description
 * @property boolean $status
 * @property integer $componentId
 * @property integer $equipmentId
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Components $component
 * @property Traveler[] $travelers
 * @property Result[] $results
 * @property Equipment[] $equipments
 * @property Equipment[] $childs
 * @property Equipment $parent
 * @property Equipment $root
 */
class Equipment extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'equipment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('description, identifier, componentId, status', 'required'),
            array('userId, componentId, parentId, position', 'numerical', 'integerOnly' => true),
            array('description', 'length', 'max' => 255),
            array('createTime, updateTime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, createTime, updateTime, description, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'parent' => array(self::BELONGS_TO, 'Equipment', 'parentId'),
            'component' => array(self::BELONGS_TO, 'Components', 'componentId'),
            'travelers' => array(self::MANY_MANY, 'Traveler', 'issue(equipmentId, travelerId)'),
            'results' => array(self::HAS_MANY, 'Result', 'equipmentId'),
            'equipments' => array(self::HAS_MANY, 'Equipment', 'parentId'),
            'equipmentCount' => array(self::STAT, 'Equipment', 'parentId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'userId' => Yii::t('default', 'User'),
            'createTime' => Yii::t('default', 'Create Time'),
            'updateTime' => Yii::t('default', 'Update Time'),
            'description' => Yii::t('default', 'Description'),
            'componentId' => Yii::t('default', 'Component'),
            'status' => Yii::t('default', 'Status'),
            'creator' => Yii::t('default', 'Creator'),
            'dateCreated' => Yii::t('default', 'Created Date'),
            'identifier' => Yii::t('default', 'Identifier'),
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
    public function search($param = array()) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria($param);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('componentId', $this->componentId);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('updateTime', $this->updateTime, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('status', $this->status);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Equipment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Prepares create_user_id and update_user_id attributes before
      saving.
     */
    protected function beforeSave() {
        if ($this->isNewRecord){
            $this->position = count(Equipment::model()->findAll("componentId = $this->componentId"))+1;
            $this->userId = Yii::app()->user->id;
        }
        return parent::beforeSave();
    }

    public function getIssues() {
        return Issue::model()->findAll("equipmentId = $this->id");
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

    public function isDeletable() {
        if (Yii::app()->user->getState('role') < 1)
            return false;
        if ($this->travelers) {
            return false;
        } elseif (Yii::app()->user->getState('role') > 2 OR Yii::app()->user->id == $this->userId) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isEditable()
    {
    	if(Yii::app()->user->id == $this->userId || Yii::app()->user->getState('role')>2)
    	{
    		return true;
    	}
    	return false;
    }

    public function getTravelersList() {
        $new = Yii::t('default', 'New Version Pending');
        $travelerId = Issue::model()->findColumn(
                'travelerId', 'equipmentId = ' . $this->id);
        $notIn = "";
        if ($travelerId) {
            $ids = '';
            foreach ($travelerId as $id) {
                $traveler = Traveler::model()->findByPk($id);
                if ($traveler->parentId) {
                    $ids .= $traveler->parentId . ',';
                } else {
                    $ids .= $traveler->id . ',';
                }
            }
            if ($ids != '') {
                $ids = substr($ids, 0, -1);
                $notIn = "AND (parentId NOT IN ($ids) OR parentId IS NULL)";
                //exit($notIn);
            }
        }
        $criteria = new CDbCriteria;
        /*$criteria->select = array("CASE status WHEN 0 THEN name+' (v'+CAST(revision as varchar)+')' ELSE name+' (v'+CAST(revision as varchar)+') -> !!$new' END as name", "id"); ORACLE*/
		$criteria->select = array("CASE status WHEN 0 THEN CONCAT(name, ' (v',   revision,  ')') ELSE CONCAT(name, ' (v', revision, ') -> !!$new') END as name", "id");
										
        $criteria->condition = "componentId = $this->componentId AND status < 1 $notIn";
        $criteria->addNotInCondition('id', $travelerId);
        ;
        $travelers = Traveler::model()->findAll($criteria);
        if ($travelers) {
            return CHtml::listData($travelers, 'id', 'name');
        } else {
            return false;
        }
    }

    public function getProjectId() {
        return $this->component->projectId;
    }

    public function getProjectName() {
        return $this->component->project->name;
    }

    public function getComponentName() {
        if ($this->component)
            return $this->component->name;
        return '';
    }
	public function getComponentId() {
        return $this->component->id;
    }

    public function getTitle() {
        return "<b>" . $this->concatenateIdentity . "</b> " . CHtml::encode($this->description);
    }

    public function getChildOptions() {
        $equipments = Equipment::model()->findAll("componentId = $this->componentId AND id=$this->id");
        return CHtml::listData($equipments, 'id', 'description');
    }

    public function getIdentify() {
        return $this->component->project->identifier . "-" . $this->title;
    }

    public function getCreator() {
        return $this->user->username;
    }

    public function getDateCreated() {
        // Format dates based on the locale
        return Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat, $this->createTime);
    }

    public function getStatutName() {
        $this->status == 0 ? $return = Yii::t('default', 'Close') : $return = Yii::t('default', 'Open');
        return $return;
    }

    public function getNotIn() {
        if ($this->parent) {
            return $this->id . ',' . $this->getRootId($this->parent);
        } else {
            return $this->id;
        }
    }

    public function getInProject() {
        $project = $this->component->project;
        $inProject = "";
        foreach ($project->components as $component) {
            $inProject .= "$component->id,";
        }
        return substr($inProject, 0, -1);
    }

    public function getRootId($equipment) {
        if ($equipment->parent) {
            return $this->getRootId($equipment->parent);
        } else {
            return $equipment->id;
        }
    }

    public function addIssue($issue) {
        $issue->equipmentId = $this->id;
        return $issue->save();
    }

    public function getConcatenateIdentity() {
        //return CHtml::encode("Project identifier: ".$this->component->project->identifier . ". </br>Component identifier: " . $this->component->identifier . ". </br>Equipment Identifier: " . $this->identifier);
		return CHtml::encode($this->component->project->identifier ." - ". $this->component->identifier .  $this->identifier);
    }
	public function getProjectIdentifier() {
		return CHtml::encode($this->component->project->identifier);
	}
	public function getComponentIdentifier() {
		return CHtml::encode($this->component->identifier);
	}
	public function getEquipmentIdentifier() {
		return CHtml::encode($this->identifier);
	}
	
    public function sortEquipments($position) {
        $i = 1;
        foreach ($position as $id) {
            $model = Equipment::model()->findByPk($id);
            $model->position = $i;
            $model->save();
            $i++;
        }
    }
    
    public function getEquipmentsForTree() {
        $sql = "SELECT CONCAT(p.identifier , ' ' , p.name) as pname, p.id as pid, CONCAT(c.identifier , ' ' , c.name) as cname,
        			c.id as cid, CONCAT(e.identifier , ' ' , e.description) as ename,e.id as eid, e.status as estatus
                    FROM project AS p 
                    INNER JOIN components AS c ON c.projectId = p.id 
                    INNER JOIN equipment  AS e ON c.id = e.componentId
                    ORDER BY p.position,c.position,e.position";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
		public function findMyEquipments()
	{
		$userId=Yii::app()->user->id;
            return self::model()->findAll(array(
                        'condition' => "userId = $userId ",
						'order'=>'updateTime',
						'limit' => 10,
						
                        
            ));//'limit' => $limit,
	
	}

}
