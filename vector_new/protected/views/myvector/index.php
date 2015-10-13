<?php
/* @var $this WarningsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('default','Warnings'),
);

$this->menu=array(
	array('label'=>Yii::t('default','Manage Warnings'), 'url'=>array('admin')),
	array('label'=>Yii::t('default','Create a Warning'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.view').click(function(){
    location.href = '/id/'+$.fn.yiiListView.getSelection(id);
    return false;
});
");
?>

<h1><?php echo Yii::t('default','Warnings') ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'updateSelector'=>'search',
        'itemsCssClass' => 'userList',
    'sortableAttributes'=>array(
        'expirationTime',
        'userId',
        'value',
    ),
)); ?>

