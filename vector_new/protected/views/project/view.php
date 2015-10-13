<?php
/* @var $this ProjectController */
/* @var $model Project */

/*
$title=Yii::t('default','Project Identifier');
Yii::app()->clientScript->registerScript('breadcrumbs', "
		document.getElementById('breadcrumbs').title='$title'; "
);


$this->breadcrumbs=array(
	//Yii::t('default','Projects')=>array('index'),
	$model->identifier,
);
*/
$this->menu=array(
	//array('label'=>Yii::t('default','List Project'), 'url'=>array('index')),
	array('label'=>Yii::t('default','Reorder Components'), 'url'=>array('components/reorder','id'=>$model->id),'visible'=>count($model->components) > 1),
	array('label'=>Yii::t('default','Update Project'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('default','Delete Project'), 'url'=>'#', 'linkOptions'=>array('class'=>'del','submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('default','Are you sure you want to delete this project?')),'visible'=>$model->isDeletable()&& Yii::app()->user->getState('role')>1),
);
?>

<h1><?php echo $model->title; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
            'identifier',    
            'name',
		
	),
)); ?>

<br>
<h1><?php echo Yii::t('default','Project Components')?></h1>

<?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$componentsDataProvider,
        'itemView'=>'/components/_view',
)); ?>
<?php
Yii::app()->clientScript->registerScript(
   'myHideEffect',
   '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>
<?php if(Yii::app()->user->hasFlash('componentSubmited')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('componentSubmited'); ?>
    </div>
<?php endif; ?>

<?php
 $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => array(
            Yii::t('default','Create Component') =>$this->renderPartial('/components/_form',array('model'=>$component),true) 
        ),
        // additional javascript options for the tabs plugin
        'options' => array(
            'collapsible' => true, 
        ),
    ));?>

