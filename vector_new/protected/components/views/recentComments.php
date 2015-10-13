<ul>
    <?php
    $recentComments = $this->getRecentComments();
     if($recentComments){
         foreach ($recentComments as $comment): ?>
        <b><?php echo $comment->user->username; ?></b> - <?php echo Yii::t('default','posted').' '.Yii::t('default','{elapsedTime} ago',array('{elapsedTime}'=>$comment->timeElapsed));?>
        <div class="issue">
            <?php echo $comment->levelText. ": ".CHtml::link(CHtml::encode($comment->equipment->concatenateIdentity." / ".$comment->text), array('step/view', 'id' => $comment->stepId,'issueId' => $comment->issueId));
            ?>
        </div>
    <?php endforeach;
    }else{
        echo Yii::t('default','No comments');
    }?>
</ul>