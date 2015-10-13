<?php
/* @var $this DiscrepancyController */
/* @var $dataProvider CActiveDataProvider */

if ($issue) {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default','Discrepancies')
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Discrepancies'),
    );
}
?>

<h1><?php echo Yii::t('default', 'Discrepancies'); ?></h1>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
));
?>
