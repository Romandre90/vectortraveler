<?php
/* @var $this ValueController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Values',
);

$this->menu=array(
	array('label'=>'Create Value', 'url'=>array('create')),
	array('label'=>'Manage Value', 'url'=>array('admin')),
);
?>

<h1>Values</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
