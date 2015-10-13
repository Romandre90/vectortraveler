<?php
/* @var $this NonconformityController */
/* @var $data Nonconformity */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('originatorId')); ?>:</b>
	<?php echo CHtml::encode($data->originatorId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createTime')); ?>:</b>
	<?php echo CHtml::encode($data->createTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activity')); ?>:</b>
	<?php echo CHtml::encode($data->activity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activityOther')); ?>:</b>
	<?php echo CHtml::encode($data->activityOther); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activityBy')); ?>:</b>
	<?php  if($data->activityBy) echo CHtml::encode($data->activityBy->username); ?>
	<br />


	<b><?php echo CHtml::encode($data->getAttributeLabel('importance')); ?>:</b>
	<?php echo CHtml::encode($data->importance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('importanceTime')); ?>:</b>
	<?php echo CHtml::encode($data->importanceTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('importanceBy')); ?>:</b>
	<?php if($data->importanceBy) echo CHtml::encode($data->importanceBy->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disposition')); ?>:</b>
	<?php echo CHtml::encode($data->disposition); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispositionBy')); ?>:</b>
	<?php if($data->dispositionBy) echo CHtml::encode($data->dispositionBy->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispositionTime')); ?>:</b>
	<?php echo CHtml::encode($data->dispositionTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispositionDescription')); ?>:</b>
	<?php echo CHtml::encode($data->dispositionDescription); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('corrective')); ?>:</b>
	<?php echo CHtml::encode($data->corrective); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('correctiveTime')); ?>:</b>
	<?php echo CHtml::encode($data->correctiveTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('correctiveBy')); ?>:</b>
	<?php if($data->correctiveBy) echo CHtml::encode($data->correctiveBy->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('closedBy')); ?>:</b>
	<?php  if($data->closedBy) echo CHtml::encode($data->closedBy->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('closedTime')); ?>:</b>
	<?php echo CHtml::encode($data->closedTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('closure')); ?>:</b>
	<?php echo CHtml::encode($data->closure); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />



</div>