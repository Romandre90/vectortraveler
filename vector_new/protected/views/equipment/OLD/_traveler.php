<div class="view">
    
        <?php 
        
        $issue = Issue::model()->getIssueByPkFk($data->id,$equipmentId); 
        $issueId = $issue->id;
        $progress = $issue->progressText;
        
        ?>

	<?php echo CHtml::link(CHtml::encode($data->name)." (v$data->revision)", array('issue/view', 'id'=>$issueId)). " <b>($progress)</b>"; ?>

</div>