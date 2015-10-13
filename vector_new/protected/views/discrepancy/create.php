<?php
/* @var $this DiscrepancyController */
/* @var $model Discrepancy */

$issueId = $issue->id;
if ($model->step->parentId) {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . ".0" => array('step/view', 'id' => $model->step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . "." . $model->step->position => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Create'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->position . ".0" => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Create'),
    );
}


$this->menu = array(
        //array('label'=>'List Discrepancy', 'url'=>array('index','travelerId'=>$model->step->travelerId)),
);
?>

<h1><?php echo Yii::t('default', 'Create Discrepancy') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'issueId' => $issueId)); ?>
