<?php
/* @var $this ElementController */
/* @var $model Element */

if ($step->parentId):
    $label = $step->parent->position . "." . $step->position;
else:
    $label = $step->position . ".0";
endif;

$this->breadcrumbs = array(
    'Travelers' => array('traveler/view', 'id' => $step->travelerId),
    $step->traveler->id => array('traveler/view', 'id' => $step->travelerId),
    'Step ' . $label => array('step/view','id'=> $step->id),
    'Manage element'
);

$this->menu=array(
	array('label'=>'List Element', 'url'=>array('index')),
	array('label'=>'Create Element', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#element-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Elements</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'element-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'typeId',
		'label',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
