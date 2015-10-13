<?php
/* @var $this IssueController */
/* @var $model Issue */
?>
<?php
$this->breadcrumbs = array(
            Yii::t('default', 'Equipments') => array('equipment/index'),
            $model->equipment->identifier=>array('equipment/view','id'=>$model->equipmentId),
            $model->traveler->name,
        );

Yii::app()->clientScript->registerScript('settings-script', <<<EOD
    $(":input").attr("disabled", true);
EOD
);


$this->menu=array(
	array('label'=>Yii::t('default','List Equipment'), 'url'=>array('equipment/index')),
        array('label'=>Yii::t('default','View Equipment'), 'url'=>array('equipment/view','id' => $model->equipmentId)),
	array('label'=>Yii::t('default','Update'), 'url'=>array('result/update', 'id'=>$model->id),'visible'=>$model->status == 1 && Yii::app()->user->getState('role')>0),
        array('label'=>Yii::t('default','Print'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'window.print()')),
	array('label' => Yii::t('default','Publish'), 'url' => '#', 'linkOptions' => array('class'=>'pub','submit' => array('publish', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to publish?')),'visible'=>$model->status && $model->complete()),
	array('label' => Yii::t('default','Unpublish'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('unpublish', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to unpublish?')),'visible'=>$model->status == 0 && Yii::app()->user->getState('role')>3 && $model->equipment->status),
        array('label'=>Yii::t('default','Delete'), 'url'=>'#', 'linkOptions'=>array('class'=>'del','submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('default','Are you sure you want to delete this issued traveler?')),'visible'=>$model->status && Yii::app()->user->getState('role')>0),
       
    );
?>
<div class="traveler">
    <div class="travelerTitle"><?php echo CHtml::encode($model->traveler->title); ?></div>
    <div class="travelerSubTitle"><?php echo " Rev. ". $model->traveler->revision?></div>
    <div class="travelerAuthor"> <?php echo Yii::t('default','by')." <b>".CHtml::encode($model->traveler->user->username); ?></b></div>
    <div class="travelerTitle"><?php echo $model->equipment->title; ?></div>
</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
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

echo "<script type='text/javascript'>
		function toggle_visibility(traceability){
				var e=document.getElementsByClassName(traceability);
				
				for (var i=0; i<e.length; i++){
				if(e[i].style.display=='block'){ 
					e[i].style.display='none';
				}
				else 	e[i].style.display='block';
			}
			}
		</script>";
echo "<div style='text-align:center'><br><a id=traceability style='color:black; text-align:center !important; background-color:rgb(200,200,200); border-radius:4px; ' onclick=toggle_visibility(&#39;info&#39;)>&nbsp;Click for extended Info&nbsp;</a></td><br></div>";//
echo $this->renderPartial('../traveler/_steps', array('model'=>new Result,'steps'=>$model->traveler->stepParent,'issueId'=>$model->id)) ;

?>