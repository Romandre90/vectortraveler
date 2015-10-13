<ul>
    
    <?php 
    $myIssues = $this->getMyIssues();
	$cont=1;
    if($myIssues){
       foreach ($myIssues as $myIssue)
	   {	
			$id=$myIssue->travelerId;
			$traveler=Traveler::model()->find("id=$id");
		echo "<span class='issue'>$cont.-  ".CHtml::link($traveler->name."- "   , array('issue/view', 'id' => $myIssue->id),array('onclick'=>'mostra_loading_screen()')). $myIssue->statusText."</br></span>";
		$cont++;
		}
	} else echo Yii::t('default', 'None');
	//CHtml::link("(v" . $myIssue->revision . ") " . $myIssue->statusText, array('view', 'id' => $myIssue->id))."</br>";
    ?>
</ul>