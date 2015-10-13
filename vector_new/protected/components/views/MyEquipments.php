<ul>
    
    <?php 
    $myEquipments = $this->getMyEquipments();
	$cont=1;
    if($myEquipments){
       foreach ($myEquipments as $myEquipment)
	   {	
		echo "<span class='equipment'> $cont.- " . CHtml::link($myEquipment->identifier . " - " , array('equipment/view', 'id' => $myEquipment->id),array('onclick'=>'mostra_loading_screen()')).date("m/d/Y",strtotime($myEquipment->updateTime))." Last update</br></span>";
		$cont++;
		}
	} else echo Yii::t('default', 'None');
	//CHtml::link("(v" . $myEquipment->revision . ") " . $myEquipment->statusText, array('view', 'id' => $myEquipment->id))."</br>";
    ?>
</ul>