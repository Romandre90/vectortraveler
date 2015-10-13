<?php
/* @var $this StepController */
/* @var $model Element */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'link-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->urlField($model,'url',array('size'=>60,'maxlength'=>510)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->