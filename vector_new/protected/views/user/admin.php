<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	Yii::t('default','Users')=>array('index'),
	Yii::t('default','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('default','List User'), 'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('default','Search Users')?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows' => 1,
        'selectionChanged' => 'function(id){ location.href = "' . $this->createUrl('update') . '/id/"+$.fn.yiiGridView.getSelection(id);}', 
	'columns'=>array(
		array(
                    'name' => 'id',
                    'filter' => CHtml::listData(User::model()->findAll(), 'id', 'ccid'),
                    'value' => 'User::Model()->FindByPk($data->id)->ccid',
                ),
		'username',
		'department',
                array(
                    'name' => 'role',
                    'filter' => CHtml::activeDropDownList($model, 'role', $model->roleOptions, array('prompt'=>Yii::t('default',''))),
                    'value' => '$data->roleText',
                ),

	),
)); ?>
