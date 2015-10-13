<?php
/* @var $this EquipmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
            Yii::t('default', 'Equipments') => array('equipment/index'),
            $model->identifier => array('equipment/view','id'=>$model->id),
            Yii::t('default','Attached Equipment'),
        
    );?>
<?php
$this->menu=array(
	array('label'=>Yii::t('default','Attach Equipment'), 'url'=>array('attach','id'=>$model->id),'visible'=>(Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->userId)),
    array('label'=>Yii::t('default','View Equipment'), 'url'=>array('equipment/view','id'=>$model->id)),
    array('label'=>Yii::t('default','List Equipment'), 'url'=>array('index')),    
    array('label'=>Yii::t('default','List Assembly'), 'url'=>array('assembly')),
);?>

<h1><?php echo Yii::t('default','Attached Equipments');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>