<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->breadcrumbs = array(
        Yii::t('default', 'Traveler templates') => array('traveler/index'),
        $id => array('traveler/view', 'id' => $id),
        Yii::t('default','Reorder Steps'),);
$this->menu = array(
    array('label' => Yii::t('default', 'View Traveler'), 'url' => array('traveler/view', 'id' => $id)),
);
?>
<h1><?php echo Yii::t('default','Reorder Steps') ?></h1>

<?php
foreach ($steps as $step){
    $items[$step->id] = "<div class='view-juici'><b>".$step->position.".0</b> ".$step->name."</div>";
}
        if ($items)
            $this->widget('zii.widgets.jui.CJuiSortable', array(
                'items' => $items,
                // additional javascript options for the JUI Sortable plugin
                'options' => array(
                    'placeholder'=>'ui-state-highlight',
                    'forcePlaceholderSize' => true,
                    'cursor'=>'move',
                    'axis'=>'y',
                    'containment'=> "#content",
                    'start' => 'js:function(event,ui){
                        start_pos = ui.item.index(); ui.item.data("start_pos",start_pos);
                        }',
                    'update'=>'js:function(event,ui){
                        start_pos = ui.item.data("start_pos") + 1;
                        end_pos = ui.item.index() + 1;
                        $.ajax({
                            type:"POST",
                            data: {"position[]":$(this).sortable("toArray")}
                        });
                     }'),
            ));
        ?> 