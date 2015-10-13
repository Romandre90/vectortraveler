<?php
/* @var $this WarningsController */
/* @var $model Warning */

$this->breadcrumbs=array(
	Yii::t('default','Warnings')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('default','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List Warnings'), 'url'=>array('index')),
	array('label'=>Yii::t('default','View Warnings'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('default','Manage Warnings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('default','Update Warning',array('{userId}'=>$model->userId)); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>