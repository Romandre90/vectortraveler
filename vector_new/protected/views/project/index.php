<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */
/*
$this->breadcrumbs=array(
	Yii::t('default','Projects'),
);
*/
$this->menu=array(
	array('label'=>Yii::t('default','Create Project'), 'url'=>array('create')),
	array('label'=>Yii::t('default','Reorder Projects'), 'url'=>array('reorder'),'visible'=>count(Project::model()->findAll())>1),
);
?>

<h1><?php echo Yii::t('default','Projects')?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
