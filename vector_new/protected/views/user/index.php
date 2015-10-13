<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */


$this->menu=array(
	array('label'=>Yii::t('default','Manage User'), 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.view').click(function(){
    location.href = '/id/'+$.fn.yiiListView.getSelection(id);
    return false;
});
");
?>

<h1><?php echo Yii::t('default','Users') ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'updateSelector'=>'search',
        'itemsCssClass' => 'userList',
    'sortableAttributes'=>array(
        'ccid',
        'username',
        'role',
        'lastLogin',
    ),
)); ?>
