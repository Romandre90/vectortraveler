<?php
/* @var $this WarningsController */
/* @var $model Warnings */

$this->breadcrumbs=array(
	Yii::t('default','Warnings')=>array('index'),
	Yii::t('default','Warnings'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List Warnings'), 'url'=>array('index')),
		array('label'=>Yii::t('default','Create a Warning'), 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('default','Search Warnings')?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'Warnings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model, 
	'columns'=>array(
		'value',
		'expirationTime',
		'createTime',
		array(
		
			'class'=>'CButtonColumn'),
	),
)); ?>
