<?php
/* @var $this StepController */
/* @var $model Step */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'step-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>
	<?php echo $form->errorSummary($model); ?>
<?php if(!is_null($model->steps) && $model->isNewRecord): ?>
	<div class="row">
            <?php if(isset($parentId)):?>
                <?php echo $form->hiddenField($model,'parentId',array('value'=>$parentId)); ?>
            <?php else: ?>
		<?php echo $form->labelEx($model,'parentId'); ?>
		<?php echo $form->dropDownList($model,'parentId',  CHtml::listData(Step::model()->getStepParent($travelerId), 'id', 'stepName'),array('prompt' => Yii::t('default','None'))); ?>
		<?php echo $form->error($model,'parentId'); ?>
            <?php endif;?>
	</div>
<?php endif; ?>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>10, 'cols'=>60)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
        


	<div class="row buttons">
		<?php if($model->isNewRecord){
                    echo CHtml::submitButton(Yii::t('default','Create'));
                }else{
                    echo CHtml::submitButton(Yii::t('default','Save')); 
                    echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('step/view','id'=>$model->id)));
                }
                
                ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->