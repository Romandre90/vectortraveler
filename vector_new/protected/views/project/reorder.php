<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1><?php echo Yii::t('default', 'Reorder Projects') ?></h1>
<?php

$this->breadcrumbs=array(
	Yii::t('default','Projects')=>array('index'),
	Yii::t('default','Reorder'),
);

$this->menu=array(
	array('label'=>Yii::t('default','Back to Projects'), 'url'=>array('index')),
);
?>
<?php
foreach ($projects as $project) {
    $items[$project->id] = "<div class='view-juici'><b> $project->position</b> " . $project->title . "</div>";
}
if ($items)
    $this->widget('zii.widgets.jui.CJuiSortable', array(
        'items' => $items,
        // additional javascript options for the JUI Sortable plugin
        'options' => array(
            'placeholder' => 'ui-state-highlight',
            'forcePlaceholderSize' => true,
            'cursor' => 'move',
            'axis' => 'y',
            'containment' => "#content",
            'start' => 'js:function(event,ui){
                        start_pos = ui.item.index(); ui.item.data("start_pos",start_pos);
                        }',
            'update' => 'js:function(event,ui){
                        start_pos = ui.item.data("start_pos") + 1;
                        end_pos = ui.item.index() + 1;
                        $.ajax({
                            type:"POST",
                            data: {"position[]":$(this).sortable("toArray")}
                        });
                     }'),
    ));
?> 