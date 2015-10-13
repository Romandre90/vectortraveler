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

					
					
					
<?php
//var_dump($_SERVER);


if(!Yii::app()->user->getState('username')) echo "<p class='warning'>First time using Vector? There is a short guide in Help tab</p></br>"; 
if (!Yii::app()->user->isGuest){
	$this->beginWidget('zii.widgets.CPortlet', array(
	'title'=>Yii::t('default','My Traveler templates'),
	));
	$this->widget('MyTravelers');
	$this->endWidget(); 
	$this->beginWidget('zii.widgets.CPortlet', array(
	'title'=>Yii::t('default','My Issued Travelers'),
	));
	$this->widget('MyIssues');
	$this->endWidget(); 
	$this->beginWidget('zii.widgets.CPortlet', array(
	'title'=>Yii::t('default','My Equipments'),
	));
	$this->widget('MyEquipments');
	$this->endWidget(); 

	} else echo Yii::t('default', 'Please, log in.');
?>
