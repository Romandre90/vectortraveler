<?php foreach($files as $file): 
if($this->uniqueid == "step" && (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $file->step->traveler->userId))
    echo CHtml::link('x',"#", 
        array('submit' => array('file/delete', 'id' => $file->id), 'confirm' => Yii::t('default','Are you sure you want to delete this file?'))
        ); ?>
<div class="file center">
    
    <?php if($file->image){
        echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/files/'.$file->link,"image",array("max-width"=>600)),Yii::app()->request->baseUrl.'/files/'.$file->link, array('target'=>'_blank'));
    }else{
        echo CHtml::link($file->fileSelected,Yii::app()->request->baseUrl.'/files/'.$file->link, array('target'=>'_blank'));
    }?>
    <div class='fileInfo'>
    <?php if($file->name):?>
    <b><?php echo CHtml::encode($file->name); ?></b><br>
    <?php endif; ?>
    <?php if($file->description):?>
    <?php echo CHtml::encode($file->description); ?>
    <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>