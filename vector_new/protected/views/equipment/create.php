<?php
/* @var $this EquipmentController */
/* @var $model Equipment */
if($component){
    $componentId = $component->id;
    $this->breadcrumbs=array(
	Yii::t('default','Projects')=>array('index'),
        $component->project->identifier=>array('project/view','id'=>$component->projectId),
	$component->identifier => array('components/view','id'=>$componentId),
        Yii::t('default','Create'),
);
}else{
    $componentId = false;
    $this->breadcrumbs=array(
	Yii::t('default','Equipments')=>array('index'),
	Yii::t('default','Create'),
);
}


$this->menu=array(
	array('label'=>Yii::t('default','List Equipment'), 'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('default','Create Equipment')?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'componentId' => $componentId)); ?>