<?php
/* @var $this WarningsController */
/* @var $data Warnings */
?>

<div class="view">



	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createTime')); ?>:</b>
	<?php echo CHtml::encode($data->createTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expirationTime')); ?>:</b>
	<?php echo CHtml::encode($data->expirationTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('priority')); ?>:</b>
	<?php if ($data->priority==1) echo "High";
		else echo "Low";?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userId')); ?>:</b>
	<?php echo CHtml::encode(User::Model()->FindByPk($data->userId)->username); ?> 
	<br />


</div>