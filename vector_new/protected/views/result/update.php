<?php
/* @var $this ResultController */
/* @var $model Issue */

$this->breadcrumbs = array(
            Yii::t('default', 'Equipments') => array('equipment/index'),
            $model->equipment->identifier => array('equipment/view','id'=>$model->equipmentId),
            $model->traveler->name => array('issue/view','id'=>$model->id),
            Yii::t('default','Update'),
        );
?>

<h1><?php echo Yii::t('default','Update issue'). " #$model->id"?></h1>
<h2><?php echo CHtml::encode($traveler->title)?></h2>
<!--<p style='color:red; text-align:center'><b><?php echo Yii::t('default','Uploaded files must have different names!')  ?></b></p> -->

<?php //echo  "<div style='text-align:center'><br><a id=traceability style='color:black; text-align:center !important; background-color:rgb(200,200,200); border-radius:4px; padding:5px; ' onclick=toggle_visibility(&#39;info&#39;)>&nbsp;Click for extended Info&nbsp;</a></td><br></div>";//
echo $this->renderPartial('_form', array('model'=>$model,'traveler'=>$traveler,'issueId'=>$issueId)); ?>