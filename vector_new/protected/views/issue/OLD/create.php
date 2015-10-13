<?php
/* @var $this EquipmentController */
/* @var $model Equipment */

$this->breadcrumbs=array(
	Yii::t('default','Equipments')=>array('equipment/index'),
        trim($equipment->identifier)=>array('equipment/view','id'=>$equipment->id),
	'Create',
);

$this->menu=array(
);
?>

<h1><?php echo Yii::t('default','Fill Traveler')?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'equipment'=>$equipment)); ?>