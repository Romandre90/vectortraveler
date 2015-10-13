<?php
/* @var $this EquipmentController */
/* @var $model Equipment */

$this->breadcrumbs=array(
	Yii::t('default','Equipments')=>array('index'),
	$model->identifier=>array('view','id'=>$model->id),
	Yii::t('default','Update'),
);

$this->menu=array(
	array('label'=>'List Equipment', 'url'=>array('index')),
	array('label'=>'View Equipment', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('default','Update Equipment')?> <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'componentId'=>$model->componentId)); ?>