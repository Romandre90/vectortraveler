<?php
/* @var $this CommentController */
/* @var $model Comment */

$this->breadcrumbs=array(
	 'Travelers'=>array('traveler/index'),
	$model->step->travelerId=>array('traveler/view','id'=>$model->step->travelerId),
	'Comments'=>array('index','travelerId'=>$model->step->travelerId),
        'Manage'=>array('comment/admin','travelerId'=>$model->step->travelerId),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Comment', 'url'=>array('index','travelerId'=>$model->step->travelerId)),
	array('label'=>'Update Comment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Comment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Comment', 'url'=>array('admin','travelerId'=>$model->step->travelerId)),
);
?>

<h1>View Comment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'text',
		'createTime',
            'updateTime',
		'userId',
	),
)); ?>
