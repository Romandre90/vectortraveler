<?php
/* @var $this ValueController */
/* @var $model Value */

$this->breadcrumbs=array(
	'Values'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Value', 'url'=>array('index')),
	array('label'=>'Create Value', 'url'=>array('create')),
	array('label'=>'View Value', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Value', 'url'=>array('admin')),
);
?>

<h1>Update Value <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>