<?php
/* @var $this DiscrepancyController */
/* @var $model Discrepancy */

$issueId = $issue->id;
$step = $model->step;
if ($model->step->parentId) {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->parent->position . ".0" => array('view', 'id' => $step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $step->parent->position . "." . $step->position => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'DR Number') . ' ' . $model->id => array('discrepancy/view', 'id' => $model->id),
        Yii::t('default', 'Update'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->position . ".0" => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'DR Number') . ' ' . $model->id => array('discrepancy/view', 'id' => $model->id),
        Yii::t('default', 'Update')
    );
}

$this->menu = array(
    array('label' => Yii::t('default', 'View Discrepancy'), 'url' => array('view', 'id' => $model->id)),
    array('label' => Yii::t('default', 'Delete Discrepancy'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this nonconformity report?'),'visible'=>$model->visibleDelete)),
);
?>

<h1><?php echo Yii::t('default', 'Update Discrepancy') ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>