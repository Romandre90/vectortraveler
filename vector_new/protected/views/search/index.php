<?php
/* @var $this WarningsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('default','Search'),
);



?>

<h1><?php echo Yii::t('default','Search') ?></h1>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="row">
		<?php echo $form->labelEx($model,'Search'); ?>
		<?php echo $form->textField($model,'word',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'word'); ?>
	</div>
        






<?php $this->endWidget(); ?>