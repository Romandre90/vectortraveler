<?php
/* @var $this ElementController */
/* @var $model Element */

$this->breadcrumbs=array(
	'Elements'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Element', 'url'=>array('index')),
	array('label'=>'Manage Element', 'url'=>array('admin')),
);
?>

<h1>Create Element</h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>