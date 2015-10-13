<?php

/**
 * This is the model class for table "step".
 *
 * The followings are the available columns in table 'step':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $travelerId
 * @property string $createTime
 * @property string $updateTime
 * @property integer $parentId
 * @property integer $position

 *
 * The followings are the available model relations:
 * @property Traveler $traveler
 * @property Step $parent
 * @property Step[] $steps
 * @property Comment[] $comments
 * @property File[] $files
 * @property Element[] $elements
 */
class Step extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Step the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'step';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, travelerId', 'required'),
            array('travelerId, parentId, position', 'numerical', 'integerOnly' => true),
            array('description, createTime, updateTime', 'safe'),
			//array('excel','file','types'=>'xlsx'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, travelerId, createTime, updateTime, parentId, position', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'traveler' => array(self::BELONGS_TO, 'Traveler', 'travelerId'),
            'parent' => array(self::BELONGS_TO, 'Step', 'parentId'),
            'steps' => array(self::HAS_MANY, 'Step', 'parentId', 'order' => 'position'),
            'stepCount' => array(self::STAT, 'Step', 'parentId'),
            'comments' => array(self::HAS_MANY, 'Comment', 'stepId'),
            'files' => array(self::HAS_MANY, 'File', 'stepId'),
            'commentCount' => array(self::STAT, 'Comment', 'stepId'),
            'elements' => array(self::HAS_MANY, 'Element', 'stepId', 'order' => 'position',),
            'elementCount' => array(self::STAT, 'Element', 'stepId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('default', 'Name'),
            'description' >= Yii::t('default', 'Description'),
            'travelerId' => Yii::t('default', 'Traveler'),
            'createTime' => Yii::t('default', 'Create Time'),
            'updateTime' => Yii::t('default', 'Update Time'),
            'parentId' => Yii::t('default', 'Parent'),
            'position' >= Yii::t('default', 'Position'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->travelerId);
        $criteria->compare('travelerId', $this->travelerId);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('updateTime', $this->updateTime, true);
        $criteria->compare('parentId', $this->parentId);
        $criteria->compare('position', $this->position, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

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

    public function getComment($issueId = null) {
        if ($issueId) {
            return Comment::model()->findAll("stepId = $this->id AND issueId = $issueId");
        } else {
            return Comment::model()->findAll("stepId = $this->id AND issueId IS NULL");
        }
    }

    public function getStepParent($travelerId) {
        return $this->findAll(array("condition" => "travelerId = $travelerId and parentId IS NULL", 'order' => 'position'));
    }

    public function getStepChild($stepId) {
        return $this->findAll(array("condition" => "parentId = $stepId", 'order' => 'position'));
    }

    public function getStepName() {
        return $this->position . ".0 " . $this->name;
    }

    protected function beforeDelete() {
        if (is_null($this->parentId)) {
            $this->reorder($this->findAll(array("condition" => "travelerId = $this->travelerId and parentId IS NULL and position > $this->position")));
        } else {
            $this->reorder($this->findAll(array("condition" => "parentId = $this->parentId and position > $this->position")));
        }
        foreach ($this->files as $file) {
            $file->delete();
        }
        foreach ($this->steps as $step) {
            $step->delete();
        }
        return parent::beforeDelete();
    }

    protected function beforeSave() {
        if ($this->isNewRecord) {
            if ($this->parentId == "") {
                $this->position = count($this->getStepParent($this->travelerId)) + 1;
            } else {
                $this->position = count($this->getStepChild($this->parentId)) + 1;
            }
        }
        return parent::beforeSave();
    }

    protected function reorder($steps) {
        foreach ($steps as $step) {
            $step->position = $step->position - 1;
            $step->save();
        }
    }

    public function addComment($comment) {
        $comment->stepId = $this->id;
        $upload = CUploadedFile::getInstance($comment, 'fileSelected');
        if ($upload) {
            $rnd = rand(0, 99999);
            $fileName = "{$rnd}-{$upload}";
            $comment->fileSelected = preg_replace("/[^a-zA-Z0-9\/_|.-]/", "_", $fileName);
            $upload->saveAs(Yii::app()->params['dfs'] . "/comment/$comment->fileSelected");
        }

        return $comment->save();
    }

    public function addSubStep($subStep) {
        $subStep->travelerId = $this->travelerId;
        $subStep->parentId = $this->id;
        return $subStep->save();
    }

    public function addFile($file) {
        $file->stepId = $this->id;
        return $file->save();
    }

    public function addElement($element, $multi = null) {
        if (is_null($multi)) {
            $element->stepId = $this->id;
            $element->position = $this->elementCount + 1;
            return $element->save();
        } else {
            $element->stepId = $this->id;
            $element->position = $this->elementCount + 1;
            $toReturn = $element->save();
            $idElement = $element->getPrimaryKey();
            foreach ($multi as $key => $value) {
                $newValue = new Value;
                $newValue->value = $value;
                $newValue->elementId = $idElement;
                $newValue->save();
            }
            return $toReturn;
        }
    }
    
    public function addGrid($element, $rows, $columns) {
		
		$element->stepId = $this->id;
        $element->position = $this->elementCount + 1;
        $toReturn = $element->save();
        $idElement = $element->getPrimaryKey();
        foreach ($rows as $key => $value) {
            
			$row = new Value;
            $row->value = $value;
            $row->elementId = $idElement;
            $row->colonne = false;
            $row->save();
        }
        foreach ($columns as $key => $value) {
            $column = new Value;
            $column->value = $value;
            $column->elementId = $idElement;
            $column->colonne = true;
            $column->save();
        }
        return $toReturn;
    }

    public function countComment($issueId = null) {
        if (is_null($issueId)) {
            $result = Comment::model()->findAll("stepId = $this->id AND issueId IS NULL");
            return count($result);
        } else {
            $result = Comment::model()->findAll("stepId = $this->id AND issueId = $issueId");
            return count($result);
        }
    }

    public function copy($travelerId) {
        $elements = $this->elements;
        $subSteps = $this->steps;
        $files = $this->files;
        $this->id = null;
        $this->isNewRecord = true;
        $this->travelerId = $travelerId;
        $this->save();
        $stepId = $this->id;
        foreach ($elements as $element) {
            $element->copy($stepId);
        }
        foreach ($files as $file) {
            $file->copy($stepId);
        }
        foreach ($subSteps as $subStep) {
            $subStepElements = $subStep->elements;
            $subStepFiles = $subStep->files;
            $subStep->id = null;
            $subStep->isNewRecord = true;
            $subStep->parentId = $stepId;
            $subStep->travelerId = $travelerId;
            $subStep->save();
            $subStepId = $subStep->id;
            foreach ($subStepElements as $subStepElement) {
                $subStepElement->copy($subStepId);
            }
            foreach ($subStepFiles as $subStepFile) {
                $subStepFile->copy($subStepId);
            }
        }
    }

    public function getProgress($issueId = null) {
        if (is_null($issueId))
            return null;
        $ok = 0;
        $count = 0;
        foreach ($this->elements as $element) {
            if ($element->typeId < 10) {
                $count++;
                if ($element->getResult($issueId)) {
                    $ok++;
                }
            }
        }
        foreach ($this->steps as $step) {
            foreach ($step->elements as $subElement) {
                if ($subElement->typeId < 10) {
                    $count++;
                    if ($subElement->getResult($issueId)) {
                        $ok++;
                    }
                }
            }
        }
        if ($count == 0)
            return null;
        return round(($ok / $count) * 100);
    }

    public function getProgressText($issueId = null) {
        if (is_null($this->getProgress($issueId))) {
            return null;
        } else {
            return " (" . $this->getProgress($issueId) . "%)";
        }
    }

    public function haveDiscrepancy($issueId = null) {
        if ($issueId) {
            if ($this->getDiscrepancy($issueId))
                return true;
            if ($this->steps) {
                foreach ($this->steps as $step) {
                    if ($step->getDiscrepancy($issueId))
                        return true;
                }
            }
        }
        return false;
    }

    public function getDiscrepancy($issueId = null) {
        if ($issueId) {
            $discrepancy = Nonconformity::model()->find("issueId = $issueId and stepId = $this->id");
            if ($discrepancy)
                return $discrepancy;
        }
        return false;
    }

    public function sortElement($position) {
        $i = 1;
        foreach ($position as $id) {
            $element = Element::model()->findByPk($id);
            $element->position = $i;
            $element->save();
            $i++;
        }
    }

    public function sortSteps($position) {
        $i = 1;
        foreach ($position as $id) {
            $step = Step::model()->findByPk($id);
            $step->position = $i;
            $step->save();
            $i++;
        }
    }

}