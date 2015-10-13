<?php

/**
 * This is the model class for table "components".
 *
 * The followings are the available columns in table 'components':
 * @property integer $id
 * @property string $name
 * @property string $identifier
 * @property integer $projectId
 * @property integer $position
 *
 * The followings are the available model relations:
 * @property Traveler[] $travelers
 * @property Equipment[] $equipments
 * @property Project $project
 */
class Components extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'components';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, identifier, projectId', 'required'),
            array('projectId', 'numerical', 'integerOnly' => true),
            array('position', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max' => 50),
            array('identifier', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, identifier, projectId', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if ($this->isNewRecord){
            $this->position = count(Components::model()->findAll("projectId = $this->projectId"))+1;
        }
        return parent::beforeSave();
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'travelers' => array(self::HAS_MANY, 'Traveler', 'componentId'),
            'project' => array(self::BELONGS_TO, 'Project', 'projectId'),
            'equipments' => array(self::HAS_MANY, 'Equipment', 'componentId','order'=>'position'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Name'),
            'identifier' => Yii::t('default', 'Identifier'),
            'projectId' => Yii::t('default', 'Project'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('identifier', $this->identifier, true);
        $criteria->compare('projectId', $this->projectId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Components the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function isDeletable() {
        if ($this->travelers) {
            return false;
        } else {
            if($this->equipments)
                return false;
        }
        return true;
    }

    public function getProjectGroups() {
        $sql = "SELECT p.name AS grouping, c.id, c.name AS text,  c.identifier, p.identifier as projectIdentifier
                    FROM     project AS p INNER JOIN
                            components AS c ON c.projectId = p.id";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public function getTitle() {
        return trim($this->identifier) . "-" . $this->name;
    }

    public function getIdentify() {
        return $this->project->identifier . "-" . $this->identifier;
    }

    public function getHide() {
        $userId = Yii::app()->user->id;
        if ($userId) {
            $preference = Preference::model()->find("userId = $userId AND hideProject = 'c$this->id'");
            if ($preference) {
                return false;
            } else {
                return true;
            }
        }return true;
    }

    public function addEquipment($equipment) {
        $equipment->componentId = $this->id;
        return $equipment->save();
    }
    
    public function sortComponents($position) {
        $i = 1;
        foreach ($position as $id) {
            $model = Components::model()->findByPk($id);
            $model->position = $i;
            $model->save();
            $i++;
        }
    }

}
