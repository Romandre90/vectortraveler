<?php

/**
 * This is the model class for table "value".
 *
 * The followings are the available columns in table 'value':
 * @property integer $id
 * @property string $value
 * @property integer $elementId
 * @property boolean $colonne
 *
 * The followings are the available model relations:
 * @property Element $element
 */
class Value extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Value the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'value';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('value, elementId', 'required'),
            array('id, elementId', 'numerical', 'integerOnly' => true),
            array('value', 'length', 'max' => 50),
            array('colonne', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, value, elementId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'element' => array(self::BELONGS_TO, 'Element', 'elementId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'value' => Yii::t('default', 'Value'),
            'elementId' => Yii::t('default', 'Element'),
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
        $criteria->compare('value', $this->value, true);
        $criteria->compare('elementId', $this->elementId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function copy($elementId) {
        $this->id = null;
        $this->isNewRecord = true;
        $this->elementId = $elementId;
        $this->save();
    }

}