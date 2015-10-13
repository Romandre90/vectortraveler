<?php
/* @var $this WarningsController */
/* @var $model Warnings */

$this->breadcrumbs=array(
	'Warnings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Warnings', 'url'=>array('index')),
	array('label'=>'Manage Warnings', 'url'=>array('admin')),
);
?>

<h1>Create a warning</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>