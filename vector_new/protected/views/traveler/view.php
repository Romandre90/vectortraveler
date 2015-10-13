<?php
/* @var $this TravelerController */
/* @var $model Traveler */
/* @var $model Step */
Yii::app()->clientScript->registerScript('settings-script', <<<EOD
    $(".view :input").attr("disabled", true);
EOD
);


$title=Yii::t('default','Project >> Component > Traveler id');
Yii::app()->clientScript->registerScript('breadcrumbs', "
		document.getElementById('breadcrumbs').title='$title';" 
);

//breadcrumbs depends on the role to have links. Users below 2nd role dont have links
if(Yii::app()->user->getState('role')>1){
$this->breadcrumbs = array(
	$model->project->name => array('project/view','id'=>$model->project->id),
	$model->component->name => array('components/view','id'=>$model->componentId),
    $model->id,
);
} else {
$this->breadcrumbs = array(
	$model->project->name,
	$model->component->name,
    $model->id,
);

}

$this->menu = array(
    //array('label' => Yii::t('default','List Traveler'), 'url' => array('index')),
    array('label'=>Yii::t('default','Print'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'window.print()')),
    array('label' => Yii::t('default','View modification'), 'url'=> array('modification','id' => $model->id),'visible'=> $model->modification != ''),
    array('label' => Yii::t('default','Change modification'), 'url' => array('modify', 'id' => $model->id), 'visible'=> $model->status == 1 && (Yii::app()->user->getState('role')>1),'linkOptions'=>array('class'=>'pub','confirm' => Yii::t('default','Are you sure you want to change the modification?'))),
	array('label' => Yii::t('default','Reorder Steps'), 'url' => array('reorder','id' => $model->id),'visible'=> $model->canReorder),
    array('label' => Yii::t('default','Create Revision'), 'url' => array('revision', 'id' => $model->id), 'visible' => $model->status == 0 && (Yii::app()->user->getState('role')>1),'linkOptions'=>array('class'=>'pub','confirm' => Yii::t('default','Are you sure you want to create a revision?'))),
    array('label' => Yii::t('default','Update Traveler'), 'url' => array('update', 'id' => $model->id), 'visible' => $model->status == 1 && (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->userId)),
    array('label' => Yii::t('default','Publish Traveler'), 'url' => '#', 'linkOptions' => array('class'=>'pub','submit' => array('publish', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to publish this traveler?')),'visible'=>$model->status == 1 && (Yii::app()->user->id  == $model->userId OR Yii::app()->user->getState('role') > 2)),
    array('label' => Yii::t('default','Delete Traveler'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to delete this traveler?')),'visible' => $model->isDeletable()),
);
?>

<div class="traveler">
    <div class="travelerTitle"><?php echo CHtml::encode($model->title); ?></div>
    <div class="travelerSubTitle"><?php echo " Rev. ". $model->revision?></div>
    <div class="travelerAuthor"> <?php echo Yii::t('default','by')." <b>".CHtml::encode($model->user->username); ?></b></div>
</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'name'=>'createTime',
            'value'=>$model->dateCreated
        ),
        array(
            'name' => 'status',
            'value' => CHtml::encode($model->statusText)
        ),
    ),
));
?>


<div id="steps">
<?php 
if($model->stepCount>=1): 
    $this->renderPartial('_steps',array('steps'=>$model->stepParent,
)); ?>
<?php endif; ?>
</div>


<?php
Yii::app()->clientScript->registerScript(
   'myHideEffect',
   '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>
<?php if(Yii::app()->user->hasFlash('stepAdded')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('stepAdded'); ?>
    </div>
<?php endif; ?>

<?php
if(Yii::app()->user->getState('role')>2 || $model->userId == Yii::app()->user->id)
if($model->status == 1)
    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => array(
            Yii::t('default','Step') =>$this->renderPartial('/step/_form',array('model'=>$step,'travelerId'=>$model->id),true),
            Yii::t('default','Import Step') =>$this->renderPartial('_import', array('model' => $model),true),
            Yii::t('default','Duplicate Step') =>$this->renderPartial('_duplicate', array('model' => $model),true),
			/*Yii::t('default','Import Traveler') =>$this->renderPartial('_importTraveler', array('model' => $model),true),*/
        ),
        // additional javascript options for the tabs plugin
        'options' => array(
            'collapsible' => true, 
        ),
    ));?>