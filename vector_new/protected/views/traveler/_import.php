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
       <?php if(Yii::app()->user->getState('role')>=2): ?> 
        <?php $data = CHtml::listData($model->getStepImport(),'id','text','grouping');?>
        <?php echo CHtml::dropDownList('stepImportForCreators','', $data,array('empty'=>"----".Yii::t('default','Select one or more Steps with Ctrl key')."----", 'multiple'=>'multiple', 'size'=>'10')); ?>
		<?php endif ?>
		<?php if(Yii::app()->user->getState('role')<2): ?>
		<?php $data = CHtml::listData($model->getStepImport(),'id','text','grouping');?>
		<?php echo CHtml::dropDownList('stepImport','', $data,array('empty'=>"--".Yii::t('default','Select a Step')."--")); ?>
		<?php endif ?>
	</div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('default','Import')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->