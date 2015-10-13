<?php
/* @var $this StepController */
/* @var $model Step */

if ($model->parentId):
    $this->breadcrumbs = array(
    Yii::t('default','Travelers') => array('traveler/index'),
    $model->traveler->id => array('traveler/view', 'id' => $model->travelerId),
    Yii::t('default','Step').' ' . $model->parent->position . ".0" => array('view','id'=>$model->parentId),
    Yii::t('default','Step').' ' . $model->parent->position . "." . $model->position => array('view','id'=>$model->id),
    Yii::t('default','Update')
);
else:
    $this->breadcrumbs = array(
    Yii::t('default','Travelers') => array('traveler/view', 'id' => $model->travelerId),
    $model->traveler->id => array('traveler/view', 'id' => $model->travelerId),
    Yii::t('default','Step').' ' . $model->position . ".0" => array('view','id'=>$model->id),
    Yii::t('default','Update'),
);
endif;

$this->menu=array(
	array('label'=>Yii::t('default','Delete Step'), 'url'=>'#', 'linkOptions'=>array('class'=>'del','submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('default','Are you sure you want to delete this step?'))),
);
?>

<h1><?php echo Yii::t('default','Update Step')?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'travelerId'=>$model->travelerId)); ?>