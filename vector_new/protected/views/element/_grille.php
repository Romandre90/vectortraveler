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
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/grille.js');
    ?>

    <p class="note"><?php echo Yii::t('default', 'Fields with <span class="required">*</span> are required') ?>.</p>

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
            <?php echo $form->dropDownList($model, 'typeId', array('13'=>'TextField','14'=>'CheckBox')); ?>
            <?php echo $form->error($model, 'typeId'); ?>
                </div>
    <div id='rows'>
        <div id='row1'><input type='text' id="r1" required name="Rows[]" maxlength="50" value="Label Row 1"><span class='dr' id='delR' onclick="delR('#row1')"> x </span></div>
    </div>
    <div id="columns">
        <div id="column1"><input type='text' id="c1" required name="Columns[]" maxlength="50" value="Label Column 1"><span class='dc' id='delC' onclick="delC('#column1')"> x </span></div>
    </div>
    
<input type='button' value="<?php echo Yii::t('default', 'Add Row') ?>" id='addRow'>
<input type='button' value="<?php echo Yii::t('default', 'Add Column') ?>" id='addColumn'>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->