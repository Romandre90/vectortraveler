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
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stepId'); ?>
		<?php echo $form->textField($model,'stepId'); ?>
		<?php echo $form->error($model,'stepId'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'discrepancyDescription'); ?>
		<?php echo $form->textArea($model,'discrepancyDescription',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'discrepancyDescription'); ?>
	</div>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'discrepancyDescriptionBy'); ?>
		<?php echo $form->textField($model,'discrepancyDescriptionBy'); ?>
		<?php echo $form->error($model,'discrepancyDescriptionBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discrepancyDescriptionDate'); ?>
		<?php echo $form->textField($model,'discrepancyDescriptionDate'); ?>
		<?php echo $form->error($model,'discrepancyDescriptionDate'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'causeOfNonconformance'); ?>
		<?php echo $form->textArea($model,'causeOfNonconformance',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'causeOfNonconformance'); ?>
	</div>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'causeOfNonconformanceBy'); ?>
		<?php echo $form->textField($model,'causeOfNonconformanceBy'); ?>
		<?php echo $form->error($model,'causeOfNonconformanceBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'causeOfNonconformanceDate'); ?>
		<?php echo $form->textField($model,'causeOfNonconformanceDate'); ?>
		<?php echo $form->error($model,'causeOfNonconformanceDate'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'disposition'); ?>
		<?php echo $form->textArea($model,'disposition',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'disposition'); ?>
	</div>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'dispositionBy'); ?>
		<?php echo $form->textField($model,'dispositionBy'); ?>
		<?php echo $form->error($model,'dispositionBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dispositionDate'); ?>
		<?php echo $form->textField($model,'dispositionDate'); ?>
		<?php echo $form->error($model,'dispositionDate'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'dispositionVerifyNote'); ?>
		<?php echo $form->textArea($model,'dispositionVerifyNote',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'dispositionVerifyNote'); ?>
	</div>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'dispositionVerifyNoteBy'); ?>
		<?php echo $form->textField($model,'dispositionVerifyNoteBy'); ?>
		<?php echo $form->error($model,'dispositionVerifyNoteBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dispositionVerifyNoteDate'); ?>
		<?php echo $form->textField($model,'dispositionVerifyNoteDate'); ?>
		<?php echo $form->error($model,'dispositionVerifyNoteDate'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'correctiveActionToPreventRecurrence'); ?>
		<?php echo $form->textArea($model,'correctiveActionToPreventRecurrence',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'correctiveActionToPreventRecurrence'); ?>
	</div>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'correctiveActionToPreventRecurrenceBy'); ?>
		<?php echo $form->textField($model,'correctiveActionToPreventRecurrenceBy'); ?>
		<?php echo $form->error($model,'correctiveActionToPreventRecurrenceBy'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'correctiveActionVerifyNote'); ?>
		<?php echo $form->textArea($model,'correctiveActionVerifyNote',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'correctiveActionVerifyNote'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'identifiedProblemArea'); ?>
		<?php echo $form->radioButtonList($model,'identifiedProblemArea',$model->getAreaOptions(), array( 'separator' => " | " )); ?>
		<?php echo $form->error($model,'identifiedProblemArea'); ?>
	</div>

        <?php echo $form->hiddenField($model,'issueId',array('value'=>$issueId)); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->