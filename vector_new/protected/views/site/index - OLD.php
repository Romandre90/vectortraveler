<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>


<?php
    Yii::app()->clientScript->registerScript('onload', $script);
?>


<h1><?php echo Yii::t('default','Welcome {username}',array('{username}'=>CHtml::encode(Yii::app()->user->getState('username'))))?></h1>
<h2><?php echo Yii::app()->dateFormatter->formatDateTime(
                    CDateTimeParser::parse(time(),'yyyy-MM-dd'
                    ),
                    'full',null) ?></h2>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>Yii::t('default','Open Nonconformities Reports'),
));
$this->widget('RecentDiscrepancies');
$this->endWidget(); ?>


<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>Yii::t('default','10 Last Comments'),
));
$this->widget('RecentComments');
$this->endWidget(); ?>