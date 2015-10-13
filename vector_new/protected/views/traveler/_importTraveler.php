<?php
/* @var $this TravelerController */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'traveler-form',
	'enableAjaxValidation'=>false,
    
)); 
?>
    <div class="row">
        
        <?php $data = CHtml::listData($model->getTravelerImport(),'id','text','group');?>
        <?php echo CHtml::dropDownList('travelerImport','', $data,array('empty'=>"--".Yii::t('default','Select a Traveler')."--", 'multiple'=>'multiple')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('default','Import')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->