<?php
/* @var $this NonconformityController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('default','Nonconformities'),
);

$this->menu=array(
	array('label'=>'Create Nonconformity', 'url'=>array('create')),
	array('label'=>'Manage Nonconformity', 'url'=>array('admin')),
);
?>

<h1>Nonconformities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
