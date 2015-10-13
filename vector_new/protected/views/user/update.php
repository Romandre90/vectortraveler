<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	Yii::t('default','Users')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('default','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List User'), 'url'=>array('index')),
	array('label'=>Yii::t('default','View User'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('default','Manage User'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('default','Update {username}',array('{username}'=>$model->username)); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>