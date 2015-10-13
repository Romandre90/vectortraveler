<?php
/* @var $this TravelerController */
/* @var $model Traveler */

$this->breadcrumbs=array(
	Yii::t('default','Travelers')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('default','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List Traveler'), 'url'=>array('index')),
        array('label' => Yii::t('default','Publish Traveler'), 'url' => '#', 'linkOptions' => array('submit' => array('publish', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to publish this traveler?')),'visible'=>$model->status == 1),
	array('label'=>Yii::t('default','View Traveler'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('default','Update Traveler').' '.$model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>