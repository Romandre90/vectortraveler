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
        Yii::t('default', 'Nonconformity') => array('nonconformity/view', 'id' => $model->id),
        Yii::t('default', 'Update'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->position . ".0" => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('nonconformity/view', 'id' => $model->id),
        Yii::t('default', 'Update')
    );
}

$this->menu = array(
    array('label' => Yii::t('default', 'View Nonconformity'), 'url' => array('view', 'id' => $model->id)),
    );
?>

<h1><?php echo Yii::t('default', 'Close Nonconformity') ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_closeout', array('model' => $model)); ?>