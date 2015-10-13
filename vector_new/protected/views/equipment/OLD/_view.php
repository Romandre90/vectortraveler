<?php
/* @var $this EquipmentController */
/* @var $data Equipment */
?>

<div class="view">

	<?php echo CHtml::link(CHtml::encode($data->component->identifier."-".$data->id), array('view', 'id'=>$data->id)); ?>

	<?php echo CHtml::encode($data->description); ?>


</div>