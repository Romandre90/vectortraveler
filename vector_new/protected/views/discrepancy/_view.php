<?php
/* @var $this DiscrepancyController */
/* @var $data Discrepancy */
?>

<div class="view">
    <!--
            <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
            
            <br />
    -->
    <h1><?php echo Yii::t('default','Report number')?>: <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?></h1>
    <div>
        <b><?php echo Yii::t('default','Step No')?>:</b>
        <div class="border"><?php
            if ($data->step->parentId):
                echo CHtml::link($data->step->parent->position . "." . $data->step->position,array('step/view','id'=>$data->stepId,'issueId'=>$data->issueId));
            else:
                echo CHtml::link($data->step->position . ".0",array('step/view','id'=>$data->stepId,'issueId'=>$data->issueId));
            endif;
            echo " - " . CHtml::encode($data->step->name);
            ?>
        </div>
    </div>
</div>
