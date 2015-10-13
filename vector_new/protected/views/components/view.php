<?php
/* @var $this ComponentsController */
/* @var $model Components */

Yii::app()->clientScript->registerScript('breadcrumbs', <<<EOD
		document.getElementById('breadcrumbs').title='Project >Component identifier'; 
EOD
);

$this->breadcrumbs=array(
    //Yii::t('default','Projects')=>array('project/index'),
	$model->project->name=>array('project/view','id'=>$model->projectId),
	$model->identifier,
);

$this->menu=array(
        array('label'=>Yii::t('default','Create Traveler'), 'url'=>array('traveler/create', 'componentId'=>$model->id)),
	array('label'=>Yii::t('default','Reorder Equipments'), 'url'=>array('equipment/reorder','id'=>$model->id),'visible'=>count($model->equipments)>1),
	array('label'=>Yii::t('default','Update Component'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('default','Delete Component'), 'url'=>'#', 'linkOptions'=>array('class'=>'del','submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('default','Are you sure you want to delete this component?')),'visible'=>$model->isDeletable()),
);
?>

<h1><?php echo CHtml::encode($model->name); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'identifier',
                'name',
		array( 'name'=>'project',
                        'value'=>$model->project->name
                        ),
	),
)); ?>
<br>
<h1><?php echo Yii::t('default','Equipments')?></h1>

<?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_equipment',
)); ?>
<?php
Yii::app()->clientScript->registerScript(
   'myHideEffect',
   '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>
<?php if(Yii::app()->user->hasFlash('equipmentSubmited')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('equipmentSubmited'); ?>
    </div>
<?php endif; ?>

<?php
 $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => array(
            Yii::t('default','Create Equipment') =>$this->renderPartial('/equipment/_form',array('model'=>$equipment, 'componentId'=>$model->id),true) 
        ),
        // additional javascript options for the tabs plugin
        'options' => array(
            'collapsible' => true, 
        ),
    ));?>

