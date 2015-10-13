<?php foreach($comments as $comment): ?>
<div class="comment <?php switch ($comment->level) {
     case 0: echo "info";
         break;
     case 1: echo "warn";
         break;
     case 2: echo "error";
         break;
     default: echo "info";
         break;
     
 }?>">
    <b><?php echo $comment->user->username; ?></b> - <?php echo Yii::t('default','posted').' '; if($comment->timeElapsed == 'maintenant' || $comment->timeElapsed == 'now'){echo $comment->timeElapsed;}else{ echo Yii::t('default','{elapsedTime} ago',array('{elapsedTime}'=>$comment->timeElapsed));};?>
    <div class="content">
        <?php echo nl2br(CHtml::encode($comment->text)); ?>
        <div class="image">
        <?php if($comment->fileSelected):?>
            <?php if(@getimagesize(Yii::app()->params['dfs']."\\comment\\".trim($comment->fileSelected))):?>
            <?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/files/comment/'.$comment->fileSelected,"image",array("max-width"=>300)),Yii::app()->request->baseUrl.'/files/comment/'.$comment->fileSelected, array('target'=>'_blank')); ?>
            <?php else: ?>
            <?php echo CHtml::link($comment->fileSelected,Yii::app()->request->baseUrl.'/files/comment/'.$comment->fileSelected, array('target'=>'_blank')); ?>
            <?php endif;?>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>