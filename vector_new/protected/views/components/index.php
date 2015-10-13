<?php
/* @var $this ComponentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Components',
);

$this->menu=array(
	array('label'=>'Create Components', 'url'=>array('create')),
	array('label'=>'Manage Components', 'url'=>array('admin')),
);
?>

<h1>Components</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
