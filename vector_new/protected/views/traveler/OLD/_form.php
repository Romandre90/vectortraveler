<?php
/* @var $this TravelerController */
/* @var $model Traveler */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'traveler-form',
	'enableAjaxValidation'=>false,
    
)); 
?>
    
	<p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

        <?php echo $form->errorSummary($model); ?>
        
        <?php 
        if($model->isNewRecord):
        if($componentId) : ?>
            <?php echo $form->hiddenField($model,'componentId',array('value'=>$componentId)); ?>
        <?php else: ?>
        <div class="row">
            <?php echo $form->labelEx($model,'componentId'); $data = CHtml::listData(Components::model()->getProjectGroups(),'id','text','group');?>
            <?php echo CHtml::activeDropDownList($model,'componentId',  $data,array('empty'=>"--".Yii::t('default','Select a Component')."--")); ?>
            <?php echo $form->error($model,'componentId'); ?>
        </div>
        <?php endif; ?>
        <?php elseif(/*$model->revision == 0  && */$model->status == 1): ?>
        <div class="row">
            <?php echo $form->labelEx($model,'componentId'); $data = CHtml::listData(Components::model()->getProjectGroups(),'id','text','group');?>
            <?php echo CHtml::activeDropDownList($model,'componentId',  $data,array('empty'=>"--".Yii::t('default','Select a Component')."--")); ?>
            <?php echo $form->error($model,'componentId'); ?>
        </div>
        <?php endif; ?>
        <div class="row">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>

	<div class="row buttons">
            <?php if($model->isNewRecord){
                    echo CHtml::submitButton(Yii::t('default','Create')); 
                    if($componentId){
                         echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('components/view','id'=>$componentId))); 
                    }else{
                         echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('traveler/index'))); 
                    }
                }else{
                    echo CHtml::submitButton(Yii::t('default','Save')); 
                    echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('traveler/view','id'=>$model->id))); 
                }?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->