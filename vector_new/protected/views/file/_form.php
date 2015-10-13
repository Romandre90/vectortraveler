<?php
/* @var $this FileController */
/* @var $model File */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'upload-form',
        'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),
                        'htmlOptions' => array('enctype' => 'multipart/form-data'),
                    )
    ); ?>
    
    <p class="note"><?php echo Yii::t('default','Fields with <span class="required">*</span> are required')?>.</p>

	<?php echo $form->errorSummary($model); ?>
    
    <div class="row">
		<?php
		echo $form->labelEx($model, 'fileSelected');
		echo "Maximum file size is 60MB</br>";
	    echo $form->fileField($model, 'fileSelected');
	    echo $form->error($model, 'file', array('clientValidation' => 'js:customValidateFile(messages)'), false, true);
		$infoFieldFileID = CHtml::activeId($model, 'fileSelected');
		?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>


<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('default','Attach')); ?>
    
                <?php if($model->discrepancyId) echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('nonconformity/view','id'=>$model->discrepancyId)));?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
<script>
    function customValidateFile(messages){
        var nameC= '#<?php echo $infoFieldFileID; ?>';
        //or var nameC= '#<?php echo $infoFieldFileID; ?>';
 
        var control = $(nameC).get(0);
 
        //simulates the required validator and allowEmpty setting of the file validator
        if (control.files.length==0) {
            messages.push("File is required");
            return false;
        }
 
        file = control.files[0];
 
        //simulates the types setting of the file validator
        if (file.name.substr((file.name.lastIndexOf('.') +1)) !='jpg') {
            messages.push("This is not a csv file");
            return false;
        }
 
       //simulates the maxSize setting of the file validator
        if (file.size>1000000) {
            messages.push("File cannot be too large (size cannot exceed 1.000.000 bytes.)");
            return false;
        }
 
       //simulates the minSize setting of the file validator
        if (file.size<100) {
            messages.push("File cannot be too small (size cannot be smaller 100 bytes.)");
            return false;
        }
 
       //simulates the format type (extra checking) see also http://en.wikipedia.org/wiki/Internet_media_type
 
        if (file.type!="image/jpeg") {
            messages.push("This is not a valid file");
            return false;
        }
 
    }
 
</script>