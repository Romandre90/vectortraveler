<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>Yii::t('default','Role'),
		));
		echo $this->user->roleDescription;
		$this->endWidget();
	?>
            
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>