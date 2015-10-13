<?php
/* @var $this CommentController */
/* @var $model Comment */

$this->breadcrumbs=array(
        'Travelers'=>array('traveler/index'),
	$model->step->travelerId=>array('traveler/view','id'=>$model->step->travelerId),
	'Comments'=>array('index','travelerId'=>$model->step->travelerId),
	'Update',
);

$this->menu=array(
	array('label'=>'List Comment', 'url'=>array('index','travelerId'=>$model->step->travelerId)),
);
?>

<h1>Update Comment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>