<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1><?php echo Yii::t('default', 'Reordersasd Steps') ?></h1>
<h2><?php echo "$step->position.0 " . CHtml::encode($step->name); ?></h2>
<?php

    $this->breadcrumbs = array(
        Yii::t('default', 'Travelers') => array('traveler/index'),
        $step->travelerId => array('traveler/view', 'id' => $step->travelerId),
        Yii::t('default', 'Step') . ' ' . $step->position . ".0" => array('step/view','id'=>$step->id),
        Yii::t('default','Reorder Steps'),
    );
$this->menu = array(
    array('label' => Yii::t('default', 'View Traveler'), 'url' => array('traveler/view', 'id' => $step->traveler->id)),
    array('label' => Yii::t('default', 'View Step'), 'url' => array('step/view', 'id' => $step->id)),
);
?>
<?php
foreach ($step->steps as $substep) {
    $items[$substep->id] = "<div class='view-juici'><b> $step->position.$substep->position</b> " . CHtml::encode($substep->name) . "</div>";
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