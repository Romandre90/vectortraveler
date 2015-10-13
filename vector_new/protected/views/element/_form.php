<?php
/* @var $this ElementController */
/* @var $model Element */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'element-form',
        'enableAjaxValidation' => false,
    ));
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/my.js');
    ?>

	<p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

<?php echo $form->errorSummary($model); ?>



    <div class="row">
        <?php echo $form->labelEx($model, 'label'); ?>
<?php echo $form->textField($model, 'label', array('size' => 50, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'label'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'help'); ?>
<?php echo $form->textField($model, 'help', array('size' => 50, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'help'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'typeId'); ?>
<?php echo $form->dropDownList($model, 'typeId', $model->getElementOptions(), array('id' => 'list')); ?>
<?php echo $form->error($model, 'typeId'); ?>
    </div>

    <div id="multi" style="display:none"> 
        <div id='TextBoxesGroup'>
            <div id="TextBoxDiv1">
                <input id="textBox1" type='text' name="Element[multi][]" maxlength="50" required="true" value="Option 1">
            </div>
        </div>
        <input type='button' value="<?php echo Yii::t('default','Add Option')?>" id='addButton'>
        <input type='button' value="<?php echo Yii::t('default','Remove Option')?>" id='removeButton'>
    </div>
        
    <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default','Create') : Yii::t('default','Save')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->