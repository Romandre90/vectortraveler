<?php
/* @var $this EquipmentController */
/* @var $model Equipment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

	<?php echo $form->errorSummary($model); ?>

        <?php if($componentId): ?>
            <?php echo $form->hiddenField($model,'componentId',array('value'=>$componentId)); ?>
            <?php echo CHtml::hiddenField('component',true); ?>
        <?php else: ?>
        <div class="row">
            <?php echo $form->labelEx($model,'componentId'); $data = CHtml::listData(Components::model()->getProjectGroups(),'id','text','group');?>
            <?php echo CHtml::activeDropDownList($model,'componentId',  $data,array('empty'=>"--".Yii::t('default','Select a Component')."--")); ?>
            <?php echo $form->error($model,'componentId'); ?>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <?php echo $form->labelEx($model,'identifier'); ?>
            <?php echo $form->textField($model,'identifier',array('size'=>20,'maxlength'=>40)); ?>
            <?php echo $form->error($model,'identifier'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($model,'description'); ?>
            <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'description'); ?>
        </div>

	<div class="row buttons">
		<?php if($model->isNewRecord){
                    echo CHtml::submitButton(Yii::t('default','Create')); 
                    if(!$componentId){
                         echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('equipment/index'))); 
                    }
                }else{
                    echo CHtml::submitButton(Yii::t('default','Save')); 
                    echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('equipment/view','id'=>$model->id))); 
                }?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->