<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */
if ($issue) {
    $issueId = $issue->id;
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Comments'),
    );
    $status = $issue->equipment->status;
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Travelers') => array('traveler/index'),
        $travelerId => array('traveler/view', 'id' => $travelerId),
        Yii::t('default', 'Comments'),);
    $status = Traveler::model()->findByPk($travelerId)->status;}
?>

<h1>Comments</h1>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'viewData' => array('status' => $status,),
));
?>
