<?php
/* @var $this ResultController */
/* @var $model Result */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'result-form',
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>
        
        <div id="steps">
            <?php 
            if($traveler->stepCount>=1): 
                $this->renderPartial('../traveler/_steps',array('steps'=>$traveler->stepParent,'issueId'=>$issueId,'save'=>$model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save'))); ?>
            <?php endif; ?>
        </div>

	<div class="row">
		<?php echo $form->hiddenField($model,'issueId',array('value'=>$issueId)); ?>
	</div>

	<div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save'),array('id'=>'save')); ?>
            <?php echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('issue/view','id'=>$model->id))); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->