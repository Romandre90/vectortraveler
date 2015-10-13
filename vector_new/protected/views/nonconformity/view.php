<?php
/* @var $this NonconformityController */
/* @var $model Nonconformity */
$issue = $model->issue;
$issueId = $issue->id;
if ($model->step->parentId) {
    
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . ".0" => array('step/view', 'id' => $model->step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . "." . $model->step->position => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity'),
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->position . ".0" => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'Nonconformity'),
    );
}

$this->menu = array(
    array('label' => Yii::t('default', 'Attachments'), 'url' => array('file/index', 'discrepancyId' => $model->id)),
    array('label' => Yii::t('default', 'View Traveler'), 'url' => array('issue/view', 'id' => $issue->id)),
    array('label' => Yii::t('default', 'View Step'), 'url' => array('step/view', 'id' => $model->stepId,'issueId' => $model->issueId)),
    array('label' => Yii::t('default', 'View Equipment'), 'url' => array('equipment/view', 'id' => $issue->equipmentId)),
    array('label' => Yii::t('default', 'Print'), 'url' => '#', 'linkOptions' => array('onclick' => 'window.print()')),
    array('label' => Yii::t('default', 'Update Nonconformity'), 'url' => array('update', 'id' => $model->id),'visible'=>$model->visible),
    array('label' => Yii::t('default', 'Close Nonconformity'), 'url' => '#', 'linkOptions' => array('class'=>'pub','submit' => array('closeout', 'id' => $model->id), 'confirm' => Yii::t('default','Are you sure you want to close this nonconformity?')),'visible'=>$model->visibleClose),
    array('label' => Yii::t('default', 'Delete Nonconformity'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'),'visible'=>$model->visibleDelete),
);
?>
<div id="discrepancy">
    <div class='header'>
        <?php if($model->files):?>
    <div class="attachments" style="text-align: right">
        <?php echo Yii::t('default', 'Attachments') . ' (' . CHtml::link($model->filesCount, array("file/index", 'discrepancyId' => $model->id)) . ")" ?>
    </div>
    <?php endif; ?>
        <table>
            <tr>
                <td colspan='2' width='10%'>    
                    <b>CERN</b><br>
                    CH-1211 Geneva 23<br>
                    Switzerland<br>
                </td>
                <td rowspan="2">
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'CERN Part Identifier'); ?></span><br>
                        <b><?php echo CHtml::link($issue->equipment->concatenateIdentity, array('equipment/view', 'id' => $issue->equipment->id)) ?></b>
                    </div>
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'EDMS Document No.') ?></span><br>
                        <b><?php if($model->edms){ echo CHtml::encode($model->edms); }else{ echo "N/A"; }?></b>
                    </div>
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'Status') ?></span><br>
                        <b><?php echo $model->statusText ?></b>
                    </div>
                </td>
                <td rowspan="2">
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', "Originator's name"); ?></span><br>
                        <b><?php echo $model->originator->username ?></b>
                    </div>
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'Importance') ?></span><br>
                        <b><?php echo $model->importanceText; ?></b>
                    </div>
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'Date') ?></span><br>
                        <b><?php echo $model->dateCreated ?></b>
                    </div>
                </td>
            </tr>
            <tr>
                <td><img src='../../images/lhclogo.jpg' width='120px'/></td>
                <td>
                    the<br>
                    <b>Large</b><br>
                    <b>Hadron</b><br>
                    <b>Collider</b><br>
                    project
                </td>
            </tr>
        </table>
    </div>

    <div class='report'>
        <h1><b><?php echo Yii::t('default', 'Nonconformity Report') ?></b></h1>
        
        <div class="wrapper">
            <div class="title"><?php echo Yii::t('default','Identification'); ?></div>
            <div class="content">
                <div><?php echo Yii::t('default','Equipment').": ".$model->equipment->title; ?></div>
                <div><?php echo Yii::t('default','Traveler').": ".CHtml::encode($model->traveler->name); ?></div>
                <div><?php echo Yii::t('default','Step').": ".CHtml::encode($model->step->name); ?></div>
            </div>
        </div>
        
        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('description')); ?></div>
            <div class="content"><b><?php echo CHtml::encode($model->description); ?></b></div>
            <div class="signature">
                <?php echo $model->descriptionBy->username; ?> -
                <?php echo $model->getDateFormat($model->descriptionTime); ?>
            </div>
        </div>
        
        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('activity')); ?></div>
            <div class="content">
                <?php echo CHtml::activeRadioButtonList($model, 'activity', $model->getActivityOptions(), array('disabled' => true)); ?>
                <?php if($model->activityOther) echo ": ". $model->activityOther ?>
            </div>
            <?php if($model->activityBy): ?>
            <div class="signature">
                <?php echo $model->activityBy->username; ?> -
                <?php echo $model->getDateFormat($model->activityTime); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('area')); ?></div>
            <div class="content" style='text-align: center'>
                <?php echo CHtml::activeRadioButtonList($model, 'area', $model->getAreaOptions(), array('disabled' => true,'separator'=> ' ')); ?>
            </div>
            <?php if($model->areaBy): ?>
            <div class="signature">
                <?php echo $model->areaBy->username; ?> -
                <?php echo $model->getDateFormat($model->areaTime); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('importance')); ?></div>
            <div class="content" style='text-align: center'>
                <?php echo CHtml::activeRadioButtonList($model, 'importance', $model->getImportanceOptions(), array('disabled' => true,'separator'=> '   ')); ?>
            </div>
            <?php if($model->importanceBy): ?>
            <div class="signature">
                <?php echo $model->importanceBy->username; ?> -
                <?php echo $model->getDateFormat($model->importanceTime); ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('disposition')); ?></div>
            <div class="content" style='text-align: center'>
                <?php echo CHtml::activeRadioButtonList($model, 'disposition', $model->getDispositionOptions(), array('disabled' => true,'separator'=> '   ')); ?>
            </div>
            <?php if($model->dispositionDescription): ?>
            <div class='description'>
                <b><?php echo CHtml::encode($model->dispositionDescription) ?></b>
            </div>
            <?php endif; ?>
            <?php if($model->dispositionBy): ?>
            <div class="signature">
                <?php echo $model->dispositionBy->username; ?> -
                <?php echo $model->getDateFormat($model->dispositionTime); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('corrective')); ?></div>
            <div class="content">
                <b><?php if($model->corrective){echo CHtml::encode($model->corrective);}else{echo "<center>N/A</center>";} ?></b>
            </div>
            <?php if($model->correctiveBy): ?>
            <div class="signature">
                <?php echo $model->correctiveBy->username; ?> -
                <?php echo $model->getDateFormat($model->correctiveTime); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="wrapper">
            <div class="title"><?php echo CHtml::encode($model->getAttributeLabel('closure')); ?></div>
            <div class="content">
                <b><?php if($model->closure){echo CHtml::encode($model->closure);}else{echo "<center>N/A</center>";} ?></b>
            </div>
            <?php if($model->closedBy): ?>
            <div class="signature">
                <?php echo $model->closedBy->username; ?> -
                <?php echo $model->getDateFormat($model->closedTime); ?>
            </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>