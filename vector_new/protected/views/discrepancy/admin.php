<?php
/* @var $this DiscrepancyController */
/* @var $model Discrepancy */

$this->breadcrumbs=array(
	Yii::t('default','Discrepancies')=>array('index'),
	Yii::t('default','Search'),
);

$this->menu=array(
	array('label'=>'List Discrepancy', 'url'=>array('index')),
	array('label'=>'Create Discrepancy', 'url'=>array('create')),
);?>



<h1><?php echo  Yii::t('default','Search Nonconformity Report')?></h1>

<p>
    <?php echo Yii::t('default','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.') ?>
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'discrepancy-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows' => 1,
        'selectionChanged' => 'function(id){ location.href = "' . $this->createUrl('view') . '/id/"+$.fn.yiiGridView.getSelection(id);}', 
	'columns'=>array(
                array(
                    'name' => 'issueId',
                    'filter' => CHtml::listData(Discrepancy::model()->listEquipment, 'id','identifier'),
                    'value' => 'Issue::model()->find("id = $data->issueId")->equipment->identifier',
                ),
                'discrepancyDescription',
                array(
                    'name' => 'discrepancyDescriptionBy',
                    'filter' => CHtml::listData(User::model()->findAll(), 'id', 'username'),
                    'value' => 'User::Model()->FindByPk($data->discrepancyDescriptionBy)->username',
                ),
                array(
                    'name' => 'status',
                    'filter' => CHtml::activeDropDownList($model, 'status', $model->statusOptions, array('prompt'=>Yii::t('default',''))),
                    'value' => '$data->getStatusText()',
                ),
            
	),
)); ?>
