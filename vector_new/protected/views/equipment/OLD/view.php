<?php
/* @var $this EquipmentController */
/* @var $model Equipment */


$this->breadcrumbs = array(
    Yii::t('default', 'Equipments') => array('index'),
    $model->identifier,
);


$this->menu = array(
    array('label' => Yii::t('default', 'List Equipment'), 'url' => array('index')),
    array('label' => Yii::t('default', 'View Assembly'), 'url' => array('assembly')),
    array('label' => Yii::t('default', 'Attach Equipment'), 'url' => array('attach','id'=> $model->id),'visible'=>(Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->userId) && $model->status),
    array('label' => Yii::t('default', "View Attached Equipment"), 'url' => array('equipment/attached','id'=>$model->id),'visible'=> count($model->equipments) > 0),
    array('label' => Yii::t('default', 'Update Equipment'), 'url' => array('update', 'id' => $model->id),'visible'=>(Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->userId) && $model->status),
    array('label' => Yii::t('default', 'Reopen Equipment'), 'url' => ' #' , 'linkOptions' => array('class'=>'del','submit'=>array('unclose', 'id' => $model->id),'confirm'=>Yii::t('default', 'Are you sure you want to reopen this equipment?')),'visible'=>(Yii::app()->user->getState('role')>3 && $model->status == 0 )),
    array('label' => Yii::t('default', 'Close Equipment'), 'url' => ' #' , 'linkOptions' => array('class'=>'pub','submit'=>array('close', 'id' => $model->id),'confirm'=>Yii::t('default', 'Are you sure you want to close this equipment?')),'visible'=>(Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->userId) && $model->status),
    array('label' => Yii::t('default', 'Delete Equipment'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this equipment?')), 'visible' => $model->isDeletable() && $model->status),
);
?>
<div class='center' style='font-size: 22px'>
    <b><?php echo $model->concatenateIdentity; ?></b> <?php echo $model->description; ?>
</div>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
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
<h1><?php echo Yii::t('default', 'Travelers') ?></h1>

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
if(Yii::app()->user->getState('role')>0 && $travelerList && $model->status )
if($model->status == 1)
    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => array(
            Yii::t('default','Fill Traveler') =>$this->renderPartial('/issue/_form',array('model'=>$issue,'equipment'=>$model,'travelerList'=>$travelerList),true) 
        ),
        // additional javascript options for the tabs plugin
        'options' => array(
            'collapsible' => true, 
        ),
    ));?>