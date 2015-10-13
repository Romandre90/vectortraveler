<?php
/* @var $this FileController */
/* @var $data File */
?>

<div class="view">

    <div class='discrepancyFile'>
        <?php if(!$data->name){ $name = $data->fileSelected; }else{ $name = $data->name;}?>
        <?php if($data->image){
             echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/files/'.$data->link),"/files/$data->link",array('title'=>$name.' - '.$data->description));
        }else{
             echo CHtml::link(CHtml::image("../../images/file.png"),"/files/$data->link",array('title'=>$name.' - '.$data->description));
        }?>
    </div>
    <div class="center">
	<?php echo CHtml::link(Yii::t('default',"Delete"),"#",array('submit'=>array('delete','id'=>$data->id),'confirm'=>Yii::t('default','Are you sure you want to delete this file?'))); ?>
    </div>
       

</div>