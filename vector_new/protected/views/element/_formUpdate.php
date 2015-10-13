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
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-te-1.4.0.min.js');
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

    <p class="note"><?php echo Yii::t('default', 'Fields with <span class="required">*</span> are required') ?>.</p>

    <?php echo $form->errorSummary($model); ?>


    <?php switch ($typeId) {
        case 10:
            ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'text'); ?>
                <?php echo $form->textArea($model, 'text', array('size' => 30, 'maxlength' => 1000,'class'=>'editor')); ?>
            <?php echo $form->error($model, 'text'); ?>
            </div>
            <?php
            break;
        case 11:
            ?>
    <div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->urlField($model,'url',array('size'=>60,'maxlength'=>510)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>
    <?php
                break;
        case 12:?>
        <div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div><?php
        if ($model->image) {
                    echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/files/' . $model->link, "image", array("max-width" => 600)), Yii::app()->request->baseUrl . '/files/' . $model->link, array('target' => '_blank'));
                } else {
                    echo CHtml::link($model->fileSelected, Yii::app()->request->baseUrl . '/files/' . $model->link, array('target' => '_blank'));
                }
        break;
        case 13:case 14:?>
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
                </div><?php
                echo "<div id='rows'>";
                foreach ($model->rows as $value){
                        echo CHtml::textField("value[$value->id]",$value->value,array('maxlength'=> 50,'required'=>true)).'<br>';
                    } 
                    echo "</div><div id='columns'>";
                    foreach ($model->columns as $value){
                        echo CHtml::textField("value[$value->id]",$value->value,array('maxlength'=> 50,'required'=>true)).'<br>';
                    } 
                    echo "</div>";
        break;
        default:
            ?>
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
            <?php echo $form->dropDownList($model, 'typeId', $model->getElementOptionsType(), array('id' => 'list')); ?>
            <?php echo $form->error($model, 'typeId'); ?>
                </div>
    
                <?php if($model->values){
                    foreach ($model->values as $value){
                        echo CHtml::textField("value[$value->id]",$value->value,array('maxlength'=> 50)).'<br>';
                    } 
                }?>
                
                <?php
            break;
            
            
    }
    ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('default', 'Save'));
        echo CHtml::link(Yii::t('default', 'Cancel'), array('step/view', 'id' => $model->stepId), array('class' => 'cancelButton'));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->