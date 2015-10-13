<?php
/* @var $this EquipmentController */
/* @var $model Equipment */
$title = Yii::t('default','Project >> Component > Equipment Identifier');
Yii::app()->clientScript->registerScript('breadcrumbs', "
		document.getElementById('breadcrumbs').title='$title'; "
);

//breadcrumbs depends on the role to have links. Users below 2nd role dont have links
if(Yii::app()->user->getState('role')>1)
{
	$this->breadcrumbs = array(
	$model->component->project->name => array('project/view','id'=>$model->component->project->id),
	$model->componentName => array('components/view','id'=>$model->componentId),
    $model->identifier,
	);
} 
else 
{
	$this->breadcrumbs = array(
	$model->component->project->name,
	$model->componentName,
    $model->identifier,
	);
}

$this->menu = array(
    array('label' => Yii::t('default', 'List Equipment'), 'url' => array('index')),
    //array('label' => Yii::t('default', 'View Assembly'), 'url' => array('assembly')),
    array('label' => Yii::t('default', 'Attach Equipment'), 'url' => array('attach','id'=> $model->id),'visible'=>(Yii::app()->user->getState('role')>1) && $model->status),
    array('label' => Yii::t('default', "View Attached Equipment"), 'url' => array('equipment/attached','id'=>$model->id),'visible'=> count($model->equipments) > 0),
    array('label' => Yii::t('default', 'Update Equipment'), 'url' => array('update', 'id' => $model->id),'visible'=>(Yii::app()->user->getState('role')>1) && $model->status),
    array('label' => Yii::t('default', 'Reopen Equipment'), 'url' => ' #' , 'linkOptions' => array('class'=>'del','submit'=>array('unclose', 'id' => $model->id),'confirm'=>Yii::t('default', 'Are you sure you want to reopen this equipment?')),'visible'=>(Yii::app()->user->getState('role')>2 && $model->status == 0 )),
    array('label' => Yii::t('default', 'Close Equipment'), 'url' => ' #' , 'linkOptions' => array('class'=>'pub','submit'=>array('close', 'id' => $model->id),'confirm'=>Yii::t('default', 'Are you sure you want to close this equipment?')),'visible'=>(Yii::app()->user->getState('role')>1) && $model->status),
    array('label' => Yii::t('default', 'Delete Equipment'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this equipment?')), 'visible' => $model->isDeletable() && $model->status && Yii::app()->user->getState('role')>1),
);
?>
<div class='center' style='font-size: 22px'>
	<div title='Equipment description'><?php echo $model->description; ?></div>
</div>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
		array(
			'name' => 'Project identifier',
			'value' => $model->projectIdentifier,),
		array(
			'name' => 'Component identifier',
			'value' => $model->componentIdentifier,),
		array(
			'name' => 'Equipment identifier',
			'value' => $model->equipmentIdentifier,),
        'creator',
        'dateCreated',
        array(
            'name' => 'status',
            'value' => $model->statutName,),
        array(
            'name' => Yii::t('default', 'Parent Equipment'),
            'type' => 'raw',
            'value' => CHtml::link($model->parentId, array('equipment/view', 'id' => $model->parentId)),
            'visible' => !is_null($model->parentId),
        ),
        array(
            'name' => Yii::t('default', 'Attached Equipment'),
            'type' => 'raw',
            'value' => CHtml::link(count($model->equipments), array('equipment/attached', 'id' => $model->id))
        ),
    ),
));
?>
<br>
<h1><?php echo Yii::t('default', 'Issued traveler templates') ?></h1>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $travelersDataProvider,
    'itemView' => '_traveler',
    'viewData' => array('equipmentId' => $model->id),
));
?>

<?php
Yii::app()->clientScript->registerScript(
   'myHideEffect',
   '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>
<?php if(Yii::app()->user->hasFlash('issueSubmited')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('issueSubmited'); ?>
    </div>
<?php endif; ?>

<?php
$travelerList = $model->getTravelersList();
if(Yii::app()->user->getState('role')>1 && $travelerList && $model->status )
if($model->status == 1 /*&& $model->isEditable()*/)
    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => array(
            Yii::t('default','Fill Traveler') =>$this->renderPartial('/issue/_form',array('model'=>$issue,'equipment'=>$model,'travelerList'=>$travelerList),true) 
        ),
        // additional javascript options for the tabs plugin
        'options' => array(
            'collapsible' => true, 
        ),
    ));?>