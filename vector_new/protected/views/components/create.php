<?php
/* @var $this ComponentsController */
/* @var $model Components */

$this->breadcrumbs=array(
	Yii::t('default','Projects')=>array('index'),
        $project->identifier=>array('project/view','id'=>$model->projectId),
	Yii::t('default','Create Component'),
);

$this->menu=array(

);
?>

<h1><?php echo Yii::t('default','Create Component')?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>