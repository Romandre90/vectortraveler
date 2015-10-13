<?php
/* @var $this NonconformityController */
/* @var $model Nonconformity */

$issueId = $model->issueId;
$issue = $model->issue;
if ($model->step->parentId) {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . ".0" => array('step/view', 'id' => $model->step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . "." . $model->step->position => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Create Nonconformity Report'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->position . ".0" => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Create Nonconformity Report'),
    );
}

$this->menu=array(
	array('label'=>Yii::t('default','View Step'), 'url'=>array('step/view','id'=>$model->stepId,'issueId'=>$model->issueId)),
	array('label'=>Yii::t('default','View Traveler'), 'url'=>array('issue/view','id'=>$model->issueId)),
);
?>

<h1><?php echo Yii::t('default', 'Create Nonconformity Report')?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>