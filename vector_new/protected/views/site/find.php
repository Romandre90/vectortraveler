<h1><?php echo Yii::t('default', 'Searching for:').' "'; ?><?php echo CHtml::encode($word).'"';?></h1>

<?php echo Yii::t('default', 'Fields with <span class="required">*</span> are required'); ?>
</br></br>
<form action="<?php echo Yii::app()->request->baseUrl ?>/index.php/site/searching" method="post">
<label><?php echo Yii::t('default', 'Select a Project')." "; ?></label></br>
<?php
echo CHtml::dropDownList('Projects',
						'', 
						$this->projects, 
						array('empty' => '(---All---)', 
																'options'=>array($projects=>array('selected'=>'selected'))))."</br>";
?>
</br>
<label><?php echo Yii::t('default', 'Select between Traveler templates or Issued Travelers'); ?><span class="required">*</span></label></br>
<?php																
echo CHtml::dropDownList('selection',
			'', 
			array( 'Issue'=>'Filled data in Issued Travelers', 
					'Traveler'=>'Labels in Traveler Templates',
					'File'=>'Files'),
			array('options'=>array($travelerVsIssue=>array('selected'=>'selected'))))."</br>"; 
	//array('empty' => '(---All---)'))."</br>";
?>	
	</br>
	<label><?php echo Yii::t('default', 'Type a text or a number'); ?><span class="required">*</span></label></br>
	<input type="text" maxlength="20" name="word" value="<?php if(isset($word)) echo $word; ?>">
	</br>
	<input type="submit" name="Search"value="<?php echo Yii::t('default', 'Find'); ?>">
</form>
</br>
<?php
if (isset($items)){
	if(!count($items)==0){
		echo "<div align='center'>".Yii::t('default', 'Results')."......... ".count($items)."</div></br>";
	}
}
?>

<?php
$cont=1;
//Issued travelers
if($travelerVsIssue=='Issue'){
	foreach($items as $item):
		$result= preg_replace("/($word)/i","<span class='red' style='background-color:#FFFF66'>$1</span>",CHtml::encode($item['value']));
		echo "<b>$cont.- <span class='results'>".$result."</span></b></br>";
		$element=$this->getElement($item['elementId']);
		$step=$this->getStep($element->stepId);
		$travelerName=$this->getTravelerName($item['issueId']);
		echo "Step: ".CHtml::link($step->name, array('step/view', 'id' => $element->stepId, 'issueId' => $item['issueId']))."</br>";
		echo "Issued traveler: ".CHtml::link($travelerName, array('issue/view', 'id' => $item['issueId']))."</br>";
		$cont++;
		echo "</br>";
	endforeach;
} 

if($travelerVsIssue=='Traveler'){ //traveler templates
	foreach($items as $item):
		//echo var_dump($item)."</br>";
		$result= preg_replace("/($word)/i","<span class='red' style='background-color:#FFFF66'>$1</span>",CHtml::encode($item['label']));
		echo "<b>$cont.- <span class='results'>".$result."</span></b></br>";
		$stepId=$item['stepId'];
		$travelerName=$item['name'];
		$stepName=$item['stepName'];
		echo "Step: ".CHtml::link($stepName, array('step/view', 'id' => $stepId, 'issueId' => ''))."</br>";
		echo "Traveler template: ".CHtml::link($travelerName, array('traveler/view', 'id' => $item['name']))."</br>";
		$cont++;
		echo "</br>";
	endforeach;
}
if($travelerVsIssue=='File'){
	foreach($items as $item):
		$result= preg_replace("/($word)/i","<span class='red' style='background-color:#FFFF66'>$1</span>",CHtml::encode($item['fileSelected']));
		echo "<b>$cont.- <span class='results'>".$result."</span></b></br>";
		if($item['stepId']== null ){
			//null stepId means is a File from an Issue
			$travelerName=$item['name'];
			//echo "es null";
			echo "Issued traveler: ".CHtml::link($travelerName, array('issue/view', 'id' => $item['id']))."</br>";
		} else {
			$stepId=$item['stepId'];
			$travelerName=$item['name'];
			echo "Step: ".CHtml::link("Step ", array('step/view', 'id' => $stepId, 'issueId' => ''))."</br>";
			echo "Traveler template: ".CHtml::link($travelerName, array('traveler/view', 'id' => $item['id']), array('target'=>'_blank'))."</br>";
		}
		$cont++;
		echo "</br>";
	endforeach;
}


?>
<?php
if(isset($items)){
	if($cont==1){
		echo "<div align='center' class='red'>".Yii::t('default', 'No results')."</div></br>";
	}else echo "<div align='center'>".Yii::t('default', 'Results')."......... ".count($items)."</div></br>";
}
echo $item['fileSelected'];
?>