<?php
/* @var $this TravelerController */
/* @var $model Traveler */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<!--
	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>
-->

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
	</div>


	<div class="row">
		<?php echo $form->label($model,'revision'); ?>
		<?php echo $form->textField($model,'revision'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', $model->getStatusOptions()); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('default','Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->