<?php
/* @var $this TravelerController */
/* @var $model Traveler */

$this->breadcrumbs = array(
    Yii::t('default','Traveler templates') => array('index'),
    Yii::t('default','Search'),
);

$this->menu = array(
    //array('label' =>  Yii::t('default','List Traveler'), 'url' => array('index')),
    array('label' => Yii::t('default', 'Create Traveler'), 'url' => array('create'), 'visible' => Yii::app()->user->getState('role') > 1),
);


?>

<h1><?php echo  Yii::t('default','Search Travelers')?></h1>

<p>
    <?php echo Yii::t('default','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.') ?>
</p>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'traveler-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'selectableRows' => 1,
    'selectionChanged' => 'function(id){ location.href = "' . $this->createUrl('view') . '/id/"+$.fn.yiiGridView.getSelection(id);}',
    'columns' => array(
        array(
            'name' => 'componentId',
            'filter' => CHtml::listData(Components::model()->getProjectGroups(),'id','identifier','projectIdentifier'),
            'value' => 'Components::Model()->FindByPk($data->componentId)->identifier',
        ),
        'name',
        array(
            'name' => 'userId',
            'filter' => CHtml::listData(User::model()->findAll(), 'id', 'username'),
            'value' => 'User::Model()->FindByPk($data->userId)->username',
        ),
        'revision',
        array(
            'name' => 'status',
            'filter' => CHtml::activeDropDownList($model, 'status', $model->statusOptions, array('prompt'=>Yii::t('default',''))),
            'value' => '$data->getStatusText()',
        ),
    ),
));
?>
