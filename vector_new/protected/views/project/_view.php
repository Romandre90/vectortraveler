<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">
	<?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id), array('class' => 'project')); ?>
</div>