<?php
/* @var $this StepController */
/* @var $model Element */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'text-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); 

Yii::app()->clientScript->registerScriptFile('/js/jquery-te-1.4.0.min.js');
Yii::app()->clientScript->registerScript('uniqueid', '$(".editor").jqte({
                "source":false,
                "color":false,
                "fsize":false,
                "format":false,
                "indent":false,
                "outdent":false,
                "left":false,
                "center":false,
                "right":false,
                "link":false,
                "unlink":false,
                "ol":false,
                "rule":false,
                "ul":false,
                });');
?>
    
    

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('size'=>30,'maxlength'=>1000,'class'=>'editor')); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->