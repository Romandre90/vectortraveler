<?php
/* @var $this ResultController */
/* @var $data Result */
?>

<div class="view">

    
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('elementId')); ?>:</b>
	<?php echo CHtml::encode($data->elementId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issueId')); ?>:</b>
	<?php echo CHtml::encode($data->issueId); ?>
	<br />


</div>