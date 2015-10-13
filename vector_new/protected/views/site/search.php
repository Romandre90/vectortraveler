<h1><?php echo Yii::t('default', 'Search page')." "; ?></h1>

<?php echo Yii::t('default', 'Fields with <span class="required">*</span> are required'); ?>
</br></br>
<form action="<?php echo Yii::app()->request->baseUrl ?>/index.php/site/searching" method="post">
<label><?php echo Yii::t('default', 'Select a Project'); ?> </label></br>
<?php
echo CHtml::dropDownList('Projects','', $this->projects, array('empty' => '(---All---)'))."</br>";

?>
</br>
<label><?php echo Yii::t('default', 'Select between Traveler templates or Issued Travelers'); ?><span class="required">*</span></label></br>
<?php	
echo CHtml::dropDownList('selection',
			'', 
			array( 'Issue'=>Yii::t('default','Filled data in Issued Travelers'), 
					'Traveler'=> Yii::t('default','Labels in Traveler Templates'),
					'File' => Yii::t('default','Files')))."</br>"; 

?>	
	</br>
	<label><?php echo Yii::t('default', 'Type a text or a number'); ?><span class="required">*</span></label></br>
	<input type="text" maxlength="20" name="word">
	</br>
	<input type="submit" name="Search"value="<?php echo Yii::t('default', 'Find'); ?>">
</form>