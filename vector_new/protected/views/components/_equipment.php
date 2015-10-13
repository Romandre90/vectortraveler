<div class="view">

	<?php echo "<span class='equipment'>".CHtml::link(CHtml::encode($data->concatenateIdentity)." ". CHtml::encode($data->description), array('equipment/view', 'id'=>$data->id, 'componentId' => $data->componentId))."</span>"; ?>

</div>
