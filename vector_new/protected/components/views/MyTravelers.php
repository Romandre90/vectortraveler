<ul>
    
    <?php 
    $myTravelers = $this->getMyTravelers();
	$cont=1;
    if($myTravelers){
       foreach ($myTravelers as $myTraveler)
	   {	
		echo "<span class='template'>   $cont.- ".CHtml::link($myTraveler->name . " - " , array('traveler/view', 'id' => $myTraveler->id), array ('class' => 'template'),array('onclick'=>'mostra_loading_screen()')). $myTraveler->statusText." - ".date("m/d/Y",strtotime($myTraveler->updateTime))." Last update</br></span>";
		$cont++;
		}
	} else echo Yii::t('default', 'None');
	//CHtml::link("(v" . $myTraveler->revision . ") " . $myTraveler->statusText, array('view', 'id' => $myTraveler->id))."</br>";
    ?>
</ul>