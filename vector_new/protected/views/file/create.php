<?php
/* @var $this FileController */
/* @var $model File */

$issueId = $issue->id;
if ($step->parentId) {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->parent->position . ".0" => array('step/view', 'id' => $step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $step->parent->position . "." . $step->position => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('nonconformity/view', 'id' => $discrepancyId),
        Yii::t('default', 'Attachments') => array('index', 'discrepancyId' => $discrepancyId),
        Yii::t('default', 'Attach'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->position . ".0" => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('nonconformity/view', 'id' => $discrepancyId),
        Yii::t('default', 'Attachments') => array('index', 'discrepancyId' => $discrepancyId),
        Yii::t('default', 'Attach'),
    );
}



$this->menu = array(
    array('label' => Yii::t('default', 'View Attachment'), 'url' => array('index', 'discrepancyId' => $discrepancyId)),
    array('label' => Yii::t('default', 'View Nonconformity'), 'url' => array('nonconformity/view', 'id' => $discrepancyId)),
);
?>

<h1><?php echo Yii::t('default', 'Attach File') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>