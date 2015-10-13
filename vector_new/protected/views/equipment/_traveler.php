<div class="view">
    
        <?php 
        
        $issue = Issue::model()->getIssueByPkFk($data->id,$equipmentId); 
        if(isset($issue)){
		$issueId = $issue->id;
        $progress = $issue->progressText;
        $pub = $issue->status;
        } else  throw new CHttpException(404, 'The requested page does not exist.');
        ?>

	<?php
	if( $pub == 1 )
		echo "<span class='issue'>".CHtml::link(CHtml::encode($data->name)." (v$data->revision)", array('issue/view', 'id'=>$issueId),array('onclick'=>'mostra_loading_screen()')). " ($progress)</span>";
	else
		echo "<span class='issue'>".CHtml::link(CHtml::encode($data->name)." (v$data->revision)", array('issue/view', 'id'=>$issueId),array('onclick'=>'mostra_loading_screen()')). " <b class=green >($progress)</b></span>";
	?>

</div>