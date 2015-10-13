<?php
/* @var $this ElementController */
/* @var $model Element */

$this->breadcrumbs=array(
	'Elements'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Element', 'url'=>array('index')),
	array('label'=>'Create Element', 'url'=>array('create')),
	array('label'=>'Update Element', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Element', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Element', 'url'=>array('admin')),
);
?>

<h1>View Element #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'typeId',
		'label',
		'disabled',
		'stepId',
	),
)); ?>
