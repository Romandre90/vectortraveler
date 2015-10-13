<?php
/* @var $this WarningsController */
/* @var $model Warnings */

$this->breadcrumbs = array(
    Yii::t('default', 'Warning') => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => Yii::t('default', 'List Warnings'), 'url' => array('index')),
    array('label' => Yii::t('default', 'Update Warning'), 'url' => array('update', 'id' => $model->id)),
    array('label' => Yii::t('default', 'Manage Warning'), 'url' => array('admin')),
);
?>

<h1><?php echo "Warning from ".$model->userId; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'value',
        'createTime',
        'expirationTime',
		array('name'=>'priority',
				'value'=> $model->priority ==1 ? 'High' : 'Low'),
        'userId',
		
    ),
));
?>
