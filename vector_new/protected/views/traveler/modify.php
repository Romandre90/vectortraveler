<?php
/* @var $this TravelerController */
/* @var $model Traveler */

$this->breadcrumbs=array(
	Yii::t('default','Traveler templates')=>array('index'),
	$id=>array('view','id'=>$id),
	Yii::t('default','Update'),
);

$this->menu=array(
	//array('label'=>Yii::t('default','List Traveler'), 'url'=>array('index')),
	array('label'=>Yii::t('default','View Traveler'), 'url'=>array('view', 'id'=>$id)),
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-te-1.4.0.min.js');
Yii::app()->clientScript->registerScript('uniqueid', '$(".editor").jqte({
                "source":false,
                "color":true,
                "fsize":false,
                "format":false,
                "indent":true,
                "outdent":true,
                "left":true,
                "center":true,
                "right":true,
                "link":false,
                "unlink":false,
                "ol":true,
                "rule":true,
                "ul":true,
                });');

?>

<h1><?php echo Yii::t('default','Create Revision {rev} For Traveler',array('{rev}'=>$model->revision)).' '.$model->id; ?></h1>
<h2><?php echo CHtml::encode($model->title)?></h2>

<p class="note"><?php echo Yii::t('default', 'Here you can log every change you made in the Traveler') ?>.</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'traveler-form',
	'enableAjaxValidation'=>false,
    
)); 
?>

<div class="row">
    <?php echo $form->labelEx($model,'modification'); ?>
    <?php echo $form->textArea($model,'modification',array('maxlength'=>1000,'class'=>'editor')); ?>
    <?php echo $form->error($model,'modification'); ?>
</div>
    
<div class="row buttons">
    <?php 
            echo CHtml::submitButton(Yii::t('default','Save')); 
            echo CHtml::button(Yii::t('default','Cancel'), array('class'=>'cancel','submit' => array('traveler/view','id'=>$id))); 
      ?>
</div>
    
    <?php $this->endWidget(); ?>

</div><!-- form -->