<?php
/* @var $this NonconformityController */
/* @var $model Nonconformity */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $userId = Yii::app()->user->id;
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'nonconformity-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));

    Yii::app()->clientScript->registerScriptFile('/js/other.js');
    
    if($model->isNewRecord){
        $descriptionDisabled = false;
        $activityDisabled = false;
        $areaDisabled = false;
        $importanceDisabled = false;
        $dispositionDisabled = false;
        $correctiveDisabled = false;
        $activityOtherDisabled = true;
    }else{
        $activityDisabled = false;
        $areaDisabled = false;
        $importanceDisabled = false;
        $dispositionDisabled = false;
        $correctiveDisabled = false;
        $activityOtherDisabled = true;
        $descriptionDisabled = $model->descriptionById != $userId;
        if($model->activity == 4){
            $activityOtherDisabled = $model->activityById!= $userId;
        }
        if($model->activityById)
            $activityDisabled = $model->activityById != $userId;
        if($model->areaById)
            $actionDisabled = $model->areaById != $userId;
        if($model->importanceById)
            $importanceDisabled = $model->importanceById != $userId;
        if($model->dispositionById)
            $dispositionDisabled = $model->dispositionById != $userId;
        if($model->correctiveById)
            $correctiveDisabled = $model->correctiveById != $userId;
    }

    ?>

    <p class="note">
        <?php if($model->isNewRecord) echo Yii::t('default','For attachments, create the nonconformity report first'); echo'<br/>'.Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>


    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50,'disabled' => $descriptionDisabled)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model, 'edms'); ?>
        <?php echo $form->textField($model, 'edms', array('size' => 50,'maxlength' => 50)); ?>
        <?php echo $form->error($model, 'edms'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'activity'); ?>
        <?php echo $form->radioButtonList($model, 'activity', $model->getActivityOptions(), array('disabled' => $activityDisabled,'onchange' => 'test(this.value)')); ?>
        <?php echo $form->textField($model, 'activityOther', array('size' => 50, 'maxlength' => 50, 'disabled' => $activityOtherDisabled)); ?>
        <?php echo $form->error($model, 'activity'); ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'area'); ?>
        <?php echo $form->radioButtonList($model, 'area', $model->getAreaOptions(), array('disabled' => $areaDisabled)); ?>
        <?php echo $form->error($model, 'area'); ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'importance'); ?>
        <?php echo $form->radioButtonList($model, 'importance', $model->getImportanceOptions(),array('disabled' => $importanceDisabled)); ?>
        <?php echo $form->error($model, 'importance'); ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'disposition'); ?>
        <?php echo $form->radioButtonList($model, 'disposition', $model->getDispositionOptions(), array('disabled' => $dispositionDisabled)); ?>
        <?php echo $form->error($model, 'disposition'); ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'dispositionDescription'); ?>
        <?php echo $form->textArea($model, 'dispositionDescription', array('rows' => 6, 'cols' => 50, 'disabled' => $dispositionDisabled)); ?>
        <?php echo $form->error($model, 'dispositionDescription'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'corrective'); ?>
        <?php echo $form->textArea($model, 'corrective', array('rows' => 6, 'cols' => 50, 'disabled' => $correctiveDisabled)); ?>
        <?php echo $form->error($model, 'corrective'); ?>
    </div>

    <div class="row buttons">
        <?php if($model->isNewRecord){
                    echo CHtml::submitButton(Yii::t('default','Create')); 
                    echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('step/view','id'=>$model->stepId,'issueId'=>$model->issueId)));
                    
                }else{
                    echo CHtml::submitButton(Yii::t('default','Save')); 
                    echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('nonconformity/view','id'=>$model->id)));
                }
                
                ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->