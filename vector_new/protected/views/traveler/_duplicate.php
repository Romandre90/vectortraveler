<?php
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
        
        <?php $data = CHtml::listData($model->getStepDuplicate(),'id','name');?>
        <?php echo CHtml::dropDownList('stepDuplicate','', $data,array('empty'=>"--".Yii::t('default','Select a Step')."--")); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('default','Duplicate')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->