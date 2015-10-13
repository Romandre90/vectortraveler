<?php
/* @var $this IssueController */
/* @var $model Issue */
?>
<?php
$title=Yii::t('default','Project >> Component >> Equipment Identifier > Traveler template name');
Yii::app()->clientScript->registerScript('breadcrumbs', "
		document.getElementById('breadcrumbs').title='$title';" 
);
//breadcrumbs depends on the role to have links. Users below 2nd role dont have links
if(Yii::app()->user->getState('role')>1){
$this->breadcrumbs = array(
            //$model->projectIdentifier => array('project/view', 'id'=> $model->projectId ),
			$model->traveler->project->name =>array('project/view', 'id'=>$model->traveler->project->id),
			$model->traveler->component->name => array('components/view', 'id'=>$model->traveler->component->id),//REVISAR
			$model->equipment->identifier=>array('equipment/view','id'=>$model->equipmentId),
            $model->traveler->name,
        );
} else {
$this->breadcrumbs = array(
            //$model->projectIdentifier => array('project/view', 'id'=> $model->projectId ),
			$model->traveler->project->name,// =>array('project/view', 'id'=>$model->traveler->project->id),
			$model->traveler->component->name,// => array('components/view', 'id'=>$model->traveler->component->id),//REVISAR
			$model->equipment->identifier,//=>array('equipment/view','id'=>$model->equipmentId),
            $model->traveler->name,
        );
}

//disable <input>tags because is not update view!
Yii::app()->clientScript->registerScript('settings-script', <<<EOD
    $(":input").attr("disabled", true);
EOD
);


$this->menu=array(
	array('label'=>Yii::t('default','List Equipment'), 'url'=>array('equipment/index')),
    array('label'=>Yii::t('default','View Equipment'), 'url'=>array('equipment/view','id' => $model->equipmentId)),
	array('label'=>Yii::t('default','Update'), 'url'=>array('result/update', 'id'=>$model->id),'visible'=>$model->status == 1 && Yii::app()->user->getState('role')>0),
    array('label'=>Yii::t('default','Print'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'window.print()')),
	array('label' => Yii::t('default','Publish'), 'url' => '#', 'linkOptions' => array('class'=>'pub','submit' => array('publish', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to publish?')),'visible'=>$model->status && ($model->complete() || Yii::app()->user->getState('role')>1) && Yii::app()->user->getState('role')>0 ),
	array('label' => Yii::t('default','Unpublish'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('unpublish', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to unpublish?')),'visible'=>$model->status == 0 && Yii::app()->user->getState('role')>2 && $model->equipment->status),
    array('label'=>Yii::t('default','Delete'), 'url'=>'#', 'linkOptions'=>array('class'=>'del','submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('default','Are you sure you want to delete this issued traveler?')),'visible'=>$model->status && Yii::app()->user->getState('role')>0),
       
    );
?>
<div class="traveler">
    <div class="travelerTitle"><?php echo CHtml::encode($model->traveler->title); ?></div>
    <div class="travelerSubTitle"><?php echo " Rev. ". $model->traveler->revision?></div>
    <div class="travelerAuthor"> <?php echo Yii::t('default','by')." <b>".CHtml::encode($model->traveler->user->username); ?></b></div>

</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
		array(
			'name' => 'Project identifier',
			'value' => $model->equipment->projectIdentifier,),
		array(
			'name' => 'Component identifier',
			'value' => $model->equipment->componentIdentifier,),
		array(
			'name' => 'Equipment identifier',
			'value' => $model->equipment->equipmentIdentifier,),
		array(
			'name' => 'Equipment description',
			'value' => $model->equipment->description,),
        'dateCreated',
        array(
            'name' => 'progress',
            'value' => CHtml::encode($model->progressText)
        ),
        array(
            'name' => 'status',
            'value' => CHtml::encode($model->statusText)
        ),
        array(
            'label' => Yii::t('default','Nonconformities'),
            'type'=>'raw',
            'value' => CHtml::link($model->traveler->getNumberDiscrepancies($model->id),array('nonconformity/index',"issueId"=>$model->id))
        ),
        array(
            'label' => Yii::t('default','Comment'),
            'type'=>'raw',
            'value' => CHtml::link($model->traveler->getNumberComments($model->id),array('comment/index',"issueId"=>$model->id))
        ),
    ),
));
?>
<?php 
Yii::app()->clientScript->registerScript('settings-script', <<<EOD
    
	$(".view :input").attr("disabled", true);
	$(".view :input.Excel").attr("disabled", false);
	$(".view :input#ajaxImage").attr("disabled", false);
	
EOD
);
echo "<script type='text/javascript'>
		function toggle_visibility(traceability){
				var e=document.getElementsByClassName(traceability);
				//kjh
				for (var i=0; i<e.length; i++){
				if(e[i].style.display=='inline'){ 
					e[i].style.display='none';
				}
				else 	e[i].style.display='inline';
			}
			}
		</script>";


echo "</br><div style='text-align:center;'><input class='Excel' type=button onclick=toggle_visibility(&#39;info&#39;) value='Click for info'><input class='Excel' type=button onclick=toggle_visibility(&#39;exceltools&#39;) value='Excel tools'></div>";
echo "<div style='text-align:center;'></div>";
echo $this->renderPartial('../traveler/_steps', array('model'=>new Result,'steps'=>$model->traveler->stepParent,'issueId'=>$model->id)) ;

?>