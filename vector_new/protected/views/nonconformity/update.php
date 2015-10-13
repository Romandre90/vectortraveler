<?php
/* @var $this NonconformityController */
/* @var $model Nonconformity */

$issue = $model->issue;
$issueId = $issue->id;
if ($model->step->parentId) {
    
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . ".0" => array('step/view', 'id' => $model->step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . "." . $model->step->position => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('view','id'=>$model->id),
        Yii::t('default','Update'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->position . ".0" => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity') => array('view','id'=>$model->id),
        Yii::t('default','Update'),
    );
}

$this->menu=array(
	array('label'=>'List Nonconformity', 'url'=>array('index')),
	array('label'=>'View Nonconformity', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Nonconformity <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>