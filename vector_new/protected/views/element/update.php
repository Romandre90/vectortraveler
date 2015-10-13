<?php
/* @var $this ElementController */
/* @var $model Element */
$step = $model->step;
if ($step->parentId):
        $this->breadcrumbs = array(
            Yii::t('default', 'Travelers') => array('traveler/index'),
            $step->travelerId => array('traveler/view', 'id' => $step->travelerId),
            Yii::t('default', 'Step') . ' ' . $step->parent->position . ".0" => array('step/view', 'id' => $step->parentId),
            Yii::t('default', 'Step') . ' ' . $step->parent->position . "." . $step->position => array('step/view', 'id' => $step->id),
            Yii::t('default','Update Element'));
    else:
        $this->breadcrumbs = array(
            Yii::t('default', 'Travelers') => array('traveler/index'),
            $step->travelerId => array('traveler/view', 'id' => $step->travelerId),
            Yii::t('default', 'Step') . ' ' . $step->position . ".0" => array('step/view', 'id' => $step->id),
            Yii::t('default','Update Element'),
        );
    endif;

$this->menu=array(
	array('label'=>'Back to Step', 'url'=>array('step/view','id'=>$model->stepId)),
        array('label'=>Yii::t('default','Delete'), 'url'=>'#', 'linkOptions'=>array('class'=>'del','submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('default','Are you sure you want to delete this element?'))),
);

if($typeId == 12){
    $model = $model->file;
}
?>

<h1>Update Element <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_formUpdate', array('model'=>$model,'typeId'=>$typeId)); ?>