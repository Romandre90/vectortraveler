<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	Yii::t('default','Projects')=>array('index'),
	$model->identifier=>array('view','id'=>$model->id),
	Yii::t('default','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List Project'), 'url'=>array('index')),
	array('label'=>Yii::t('default','View Project'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('default','Update Project')." ". $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>