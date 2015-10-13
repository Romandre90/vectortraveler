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
	'id'=>'nonconformity-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">
        <?php echo'<br/>'.Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'closure'); ?>
		<?php echo $form->textArea($model,'closure',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'closure'); ?>
	</div>

        <?php echo $form->hiddenField($model,'issueId',array('value'=>$issueId)); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->