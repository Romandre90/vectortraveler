<?php
/* @var $this NonconformityController */
/* @var $model Nonconformity */

$this->breadcrumbs=array(
	Yii::t('default','Nonconformities'),
);

$this->menu=array(
	array('label'=>'List Nonconformity', 'url'=>array('index')),
	array('label'=>'Create Nonconformity', 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('default','List of Nonconformity')?></h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'nonconformity-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows' => 1,
        'selectionChanged' => 'function(id){ location.href = "' . $this->createUrl('view') . '/id/"+$.fn.yiiGridView.getSelection(id);}', 
	'columns'=>array(
                array(
                    'header' => Yii::t('default','Step'),
                    'name' => 'id',
                    'filter' => CHtml::listData(Nonconformity::model()->travelerStepList, 'id', 'travelerStep','group'),
                    'value' => 'Nonconformity::Model()->FindByPk($data->id)->travelerStep',
                ),
		array(
                    'name' => 'originatorId',
                    'filter' => CHtml::listData(Nonconformity::model()->users, 'originatorId', 'username'),
                    'value' => 'User::Model()->FindByPk($data->originatorId)->username',
                ),
		array(
                    'name' => 'importance',
                    'filter' => CHtml::activeDropDownList($model, 'importance', $model->importanceOptions, array('prompt'=>Yii::t('default',''))),
                    'value' => '$data->getImportanceText()',
                ),
		array(
                    'name' => 'status',
                    'filter' => CHtml::activeDropDownList($model, 'status', $model->statusOptions, array('prompt'=>Yii::t('default',''))),
                    'value' => '$data->getStatusText()',
                ),
	),
)); ?>
