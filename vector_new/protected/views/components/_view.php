<?php
/* @var $this ComponentsController */
/* @var $data Components */
?>

<div class="view">

	<?php echo CHtml::link(CHtml::encode($data->title), array('components/view', 'id'=>$data->id), array('class' => 'multigear')); ?>

</div>