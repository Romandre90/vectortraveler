<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<?php Yii::app()->clientScript->registerScriptFile('../../js/filter.js'); ?>
<div class="span-5 first">
    <div id="sidebar_left">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => Yii::t('default', 'Filter'),
        ));
        if($this->uniqueid == 'traveler')
			echo "<div>" . CHtml::checkBox("archive",  Preference::model()->hideArchive, array('onChange' => 'javascript:onChangeFilter(this)')) . " <label for='archive' class='archive'>Deprecated versions</label></div><hr>";
        foreach ($this->projects as $project) {
            echo "<div>" . CHtml::checkBox("p" . $project->id, $project->hide, array('onChange' => 'javascript:onChangeFilter(this)')) . " <label for='p$project->id'>$project->identifier</label></div>";
            if ($this->action->id != "assembly") {
                if ($project->hide) {
                    $style = "style='margin-left:10px'";
                } else {
                    $style = "style='display:none;margin-left:10px'";
                }
                foreach ($project->components as $component) {

                    echo "<div class='p$component->projectId' $style>" . CHtml::checkBox("c$component->id", $component->hide, array('onChange' => 'javascript:onChangeFilter(this)')) . " <label for='c$component->id'>$component->identifier</label></div>";
                }
            }
        }
        $this->endWidget();
        ?>

    </div><!-- sidebar -->
</div>
<div class="span-14">
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<div class="span-5 last">
    <div id="sidebar">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => Yii::t('default', 'Operations'),
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu,
            'htmlOptions' => array('class' => 'operations'),
        ));
        $this->endWidget();
        ?>

    </div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>