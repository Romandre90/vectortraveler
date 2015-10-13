<?php
/* @var $this FileController */
/* @var $dataProvider CActiveDataProvider */

$issueId = $issue->id;
if ($step->parentId) {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->parent->position . ".0" => array('view', 'id' => $step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $step->parent->position . "." . $step->position => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('nonconformity/view', 'id' => $discrepancy->id),
        Yii::t('default', 'Attachments'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
        $issue->traveler->name => array('issue/view', 'id' => $issue->id),
        Yii::t('default', 'Step') . ' ' . $step->position . ".0" => array('step/view', 'id' => $step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('nonconformity/view', 'id' => $discrepancy->id),
        Yii::t('default', 'Attachments')
    );
}





$this->menu = array(
    array('label' => Yii::t('default', 'Attach File'), 'url' => array('create', 'discrepancyId' => $discrepancy->id),'visible'=>$discrepancy->visible),
    array('label' => Yii::t('default', 'Back to Nonconformity Report'), 'url' => array('nonconformity/view', 'id' => $discrepancy->id)),
);
?>

<div class="center">
    <h1><?php echo Yii::t('default', 'Nonconformity Report') ?></h1>
    <h2><?php echo Yii::t('default', 'Attachments') ?></h2>
</div>
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'itemsCssClass' => 'items-float',
));
?>
