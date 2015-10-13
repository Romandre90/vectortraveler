<?php
/* @var $this UserController */
/* @var $model Warnings */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Warnings-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>



	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>90,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>
<?php echo $form->labelEx($model,'expirationTime'); ?>
<?php
Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
	$this->widget('CJuiDateTimePicker', array(
		'model'=>$model,
		'attribute'=>'expirationTime',
		));
?>


	<div class="row">
		<?php echo $form->labelEx($model,'priority'); ?>
		<?php echo $form->dropDownList($model,'priority', array('1'=>'High', '2'=>'Low')); ?>
		<?php echo $form->error($model,'priority'); ?>
	</div>


	<div class="row buttons">
		<?php if($model->isNewRecord){
                    echo CHtml::submitButton(Yii::t('default','Create'));
                }else{
                    echo CHtml::submitButton(Yii::t('default','Save')); 
                }
                
                ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->