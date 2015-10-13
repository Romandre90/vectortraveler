<?php
/* @var $this CommentController */
/* @var $data Comment */
?>
<div class="view">
    <?php echo CHtml::link(CHtml::encode($data->step->name),array('step/view','id'=>$data->stepId,'issueId'=>$data->issueId)) ?>


<div class="comment <?php
switch ($data->level) {
    case 0: echo "info";
        break;
    case 1: echo "warn";
        break;
    case 2: echo "error";
        break;
    default: echo "info";
        break;
}
?>">
    <b><?php echo $data->user->username.$status;?></b> - <?php
    echo Yii::t('default', 'posted') . ' ';
    if ($data->timeElapsed == 'maintenant' || $data->timeElapsed == 'now') {
        echo $data->timeElapsed;
    } else {
        echo Yii::t('default', '{elapsedTime} ago', array('{elapsedTime}' => $data->timeElapsed));
    };
    ?>
    <?php if($status && $data->userId == Yii::app()->user->id): ?>
    
    <span class="delete"><?php echo CHtml::link(Yii::t('default','Delete'),"#", 
        array('submit' => array('comment/delete', 'id' => $data->id), 'confirm' => Yii::t('default','Are you sure you want to delete this comment?'))
        );?></span>
    <?php endif; ?>
    <div class="content">
            <?php echo nl2br(CHtml::encode($data->text)); ?>
        <div class="image">
            <?php if($data->fileSelected):?>
            <?php if(@getimagesize(Yii::app()->params['dfs']."\\comment\\".trim($data->fileSelected))):?>
            <?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/files/comment/'.$data->fileSelected,"image",array("max-width"=>300)),Yii::app()->request->baseUrl.'/files/comment/'.$data->fileSelected, array('target'=>'_blank')); ?>
            <?php else: ?>
            <?php echo CHtml::link($data->fileSelected,Yii::app()->request->baseUrl.'/files/comment/'.$data->fileSelected, array('target'=>'_blank')); ?>
            <?php endif;?>
<?php endif; ?>
        </div>
    </div>
</div>
</div>
