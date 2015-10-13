<?php
/* @var $this DiscrepancyController */
/* @var $model Discrepancy */
/* @var $form CActiveForm */
if(!isset($issueId)){
    $issueId = $model->issueId;
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'discrepancy-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">
        <?php if($model->isNewRecord) echo Yii::t('default','For attachments, create the discrepancy first'); echo'<br/>'.Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'closeoutNote'); ?>
		<?php echo $form->textArea($model,'closeoutNote',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'closeoutNote'); ?>
	</div>

        <?php echo $form->hiddenField($model,'issueId',array('value'=>$issueId)); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->