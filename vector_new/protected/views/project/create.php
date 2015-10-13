<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	Yii::t('default','Projects')=>array('index'),
	Yii::t('default','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List Project'), 'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('default',Yii::t('default','Create Project'))?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>