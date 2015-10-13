<?php
/* @var $this ComponentsController */
/* @var $model Components */

$this->breadcrumbs=array(
	Yii::t('default','Projects')=>array('index'),
        $model->project->identifier=>array('project/view','id'=>$model->projectId),
	$model->identifier => array('view','id'=>$model->id),
	Yii::t('default','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('default','View Component'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('default','Update Component'). " ". $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>