<?php
/* @var $this ElementController */
/* @var $data Element */
?>

<div class="view">



	<b><?php echo CHtml::encode($data->getAttributeLabel('typeId')); ?>:</b>
	<?php echo CHtml::encode($data->typeId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('label')); ?>:</b>
	<?php echo CHtml::encode($data->label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stepId')); ?>:</b>
	<?php echo CHtml::encode($data->stepId); ?>
	<br />


</div>