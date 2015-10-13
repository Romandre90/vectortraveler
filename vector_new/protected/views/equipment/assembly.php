<?php
/* @var $this EquipmentController */
/* @var $dataProvider CActiveDataProvider */

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('treeview');
$cs->registerScript('test', "jQuery(\"#treeview\").treeview({});");
$cs->registerCssFile($cs->getCoreScriptUrl() . '/treeview/jquery.treeview.css');



$this->menu = array(
    array('label' => Yii::t('default','Create Equipment'), 'url' => array('create'),'visible'=>Yii::app()->user->getState('role')>1),
    array('label'=>Yii::t('default','List Equipment'), 'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('default','Equipment Assemblies')?></h1>
<ul id='treeview' class='filetree'>
    <?php loop($equipments, 0, '') ?>
</ul>


<?php function loop($equipments, $projectId,$ul){
    foreach($equipments as $equipment):
        $component = $equipment->component;
        $project = $component->project;
        if($projectId != $component->projectId):
            $projectId = $component->projectId;
            if ($project->hide) {
                $style = "";
            } else {
                $style = "style='display:none'";
            }
            echo "$ul<li class='p$projectId' $style><span class='project' title='Project'>".$project->identifier." ".$project->name."</span>";
            $ul = "</ul></li>";
            echo "<ul>";
        endif;
        $childs = $equipment->equipments;
        if($equipment->status==1)
			echo "<li><span class='equipment'>".CHtml::link($component->identifier.$equipment->identifier,array('view','id'=>$equipment->id),array('onclick'=>'mostra_loading_screen()'))." - ".CHtml::encode($equipment->description)."</span>";
        else
        	echo "<li><span class='equipment'>".CHtml::link($component->identifier.$equipment->identifier,array('view','id'=>$equipment->id),array('onclick'=>'mostra_loading_screen()'))." - <b class=green >".CHtml::encode($equipment->description)."</b></span>";
         if($childs){
             echo "<ul>";
             loop($childs, $projectId, $ul);
             echo "</ul>";
         }else{
             echo "</li>";
         }
    endforeach;
} ?>