<?php
$this->breadcrumbs=array(
	Yii::t('default','MyVector'),
);
?>
<h1><?php echo Yii::t('default','My Vector'); ?></h1>


<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>Yii::t('default','My Traveler templates'),
));
$this->widget('MyTravelers');
$this->endWidget(); ?>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>Yii::t('default','My Issued Travelers'),
));
$this->widget('MyIssues');
$this->endWidget(); ?>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>Yii::t('default','My Equipments'),
));
$this->widget('MyEquipments');
$this->endWidget(); ?>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>Yii::t('default','My Equipments'),
));
$this->widget('MyEquipments');
$this->endWidget(); ?>




