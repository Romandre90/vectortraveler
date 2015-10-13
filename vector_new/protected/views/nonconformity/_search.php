<?php
/* @var $this NonconformityController */
/* @var $model Nonconformity */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'originatorId'); ?>
		<?php echo $form->textField($model,'originatorId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createTime'); ?>
		<?php echo $form->textField($model,'createTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activity'); ?>
		<?php echo $form->textField($model,'activity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activityOther'); ?>
		<?php echo $form->textField($model,'activityOther',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activityBy'); ?>
		<?php echo $form->textField($model,'activityBy'); ?>
	</div>



	<div class="row">
		<?php echo $form->label($model,'importance'); ?>
		<?php echo $form->textField($model,'importance'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'importanceTime'); ?>
		<?php echo $form->textField($model,'importanceTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'importanceBy'); ?>
		<?php echo $form->textField($model,'importanceBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disposition'); ?>
		<?php echo $form->textField($model,'disposition'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dispositionBy'); ?>
		<?php echo $form->textField($model,'dispositionBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dispositionTime'); ?>
		<?php echo $form->textField($model,'dispositionTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dispositionDescription'); ?>
		<?php echo $form->textArea($model,'dispositionDescription',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'corrective'); ?>
		<?php echo $form->textArea($model,'corrective',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'correctiveTime'); ?>
		<?php echo $form->textField($model,'correctiveTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'correctiveBy'); ?>
		<?php echo $form->textField($model,'correctiveBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'closedBy'); ?>
		<?php echo $form->textField($model,'closedBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'closedTime'); ?>
		<?php echo $form->textField($model,'closedTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'closure'); ?>
		<?php echo $form->textArea($model,'closure',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->checkBox($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->