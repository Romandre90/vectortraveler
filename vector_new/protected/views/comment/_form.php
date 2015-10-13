<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php $model->level = 0; echo $form->radioButtonList($model,'level',$model->getLevelOptions(), array( 'separator' => " | " )); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row">
            <?php 
            echo $form->labelEx($model, 'fileSelected');
            echo $form->fileField($model, 'fileSelected');
            echo $form->error($model, 'fileSelected')
            ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->