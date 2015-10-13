<?php
/* @var $this TravelerController */
/* @var $model Traveler */

$this->breadcrumbs = array(
    Yii::t('default','Travelers') => array('index'),
    Yii::t('default','Create'),
);

$this->menu = array(
    array('label' => Yii::t('default','List Traveler'), 'url' => array('index')),
    array('label' => Yii::t('default','Search Travelers'), 'url' => array('admin')),
);
?>

<h1><?php echo Yii::t('default','Create Traveler') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'componentId' => $componentId)); ?>