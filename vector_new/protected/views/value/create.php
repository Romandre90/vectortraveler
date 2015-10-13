<?php
/* @var $this ValueController */
/* @var $model Value */

$this->breadcrumbs=array(
	'Values'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Value', 'url'=>array('index')),
	array('label'=>'Manage Value', 'url'=>array('admin')),
);
?>

<h1>Create Value</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>