<?php
/* @var $this IssueController */
/* @var $model Issue */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'travelerId');?>
            <?php echo CHtml::activeDropDownList($model,'travelerId',  $travelerList, array('empty'=>'--'.Yii::t('default','Choose Traveler').'--')); ?>
            <?php echo $form->error($model,'travelerId'); ?>
        </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->