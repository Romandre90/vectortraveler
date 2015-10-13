<?php
/* @var $this ElementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Elements',
);

$this->menu=array(
	array('label'=>'Create Element', 'url'=>array('create')),
	array('label'=>'Manage Element', 'url'=>array('admin')),
);
?>

<h1>Elements</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
