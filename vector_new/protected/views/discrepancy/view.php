<?php
/* @var $this DiscrepancyController */
/* @var $model Discrepancy */
$issueId = $issue->id;
if ($model->step->parentId) {
    
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . ".0" => array('step/view', 'id' => $model->step->parentId, 'issueId' => $issueId),
        Yii::t('default', 'Step') . ' ' . $model->step->parent->position . "." . $model->step->position => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'DR Number') . ' ' . $model->id,
    );
} else {
    $this->breadcrumbs = array(
        Yii::t('default', 'Equipments') => array('equipment/index'),
        $issue->equipment->identifier=>array('equipment/view','id'=>$issue->equipmentId),
        $issue->traveler->name=>array('issue/view','id'=>$issue->id),
        Yii::t('default', 'Step') . ' ' . $model->step->position . ".0" => array('step/view', 'id' => $model->step->id, 'issueId' => $issueId),
        Yii::t('default', 'DR Number') . ' ' . $model->id,
    );
}

$this->menu = array(
    array('label' => Yii::t('default', 'Attachments'), 'url' => array('file/index', 'discrepancyId' => $model->id)),
    array('label' => Yii::t('default', 'View Traveler'), 'url' => array('issue/view', 'id' => $issue->id)),
    array('label' => Yii::t('default', 'View Step'), 'url' => array('step/view', 'id' => $model->stepId,'issueId' => $model->issueId)),
    array('label' => Yii::t('default', 'View Equipment'), 'url' => array('equipment/view', 'id' => $issue->equipmentId)),
    array('label' => Yii::t('default', 'Print'), 'url' => '#', 'linkOptions' => array('onclick' => 'window.print()')),
    array('label' => Yii::t('default', 'Close Discrepancy'), 'url' => array('closeout', 'id' => $model->id),'visible'=>$model->visibleClose),
    array('label' => Yii::t('default', 'Update Discrepancy'), 'url' => array('update', 'id' => $model->id),'visible'=>$model->visible),
    array('label' => Yii::t('default', 'Delete Discrepancy'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this nonconformity report?')),'visible'=>$model->visibleDelete),
);
?>

<div id="discrepancy">
    
    <div class='header'>

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
                        <b><?php echo CHtml::link($issue->equipment->identifier, array('equipment/view', 'id' => $issue->equipment->id)) ?></b>
                    </div>
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'Non Conformity No.') ?></span><br>
                        <b><?php echo $model->id ?></b>
                    </div>
                </td>
                <td rowspan="2">
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', "Orignator's name"); ?></span><br>
                        <b><?php echo $model->discrepancyDescriptionBy0->username ?></b>
                    </div>
                    <div class="rond">
                        <span class="label"><?php echo Yii::t('default', 'Traveler No.') ?></span><br>
                        <b><?php echo CHtml::link($issue->travelerId, array('issue/view', 'id' => $issue->id)); ?></b>
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
    <?php if($model->filesCount > 0):?>
    <div class="attachments" style="text-align: right">
        <?php echo Yii::t('default', 'Attachments') . ' (' . CHtml::link($model->filesCount, array("file/index", 'discrepancyId' => $model->id)) . ")" ?>
    </div>
    <?php endif; ?>
    <div class='report'>
        <h1><?php echo Yii::t('default', 'Nonconformity Report') ?></h1>
        <div class="info">
            <b><?php echo Yii::t('default', 'Traveler Title') ?>:</b>
            <div class="border"><?php echo CHtml::encode($model->step->traveler->title) ?></div>
            <b><?php echo Yii::t('default', 'Step No') ?>:</b>
            <div class="border"><?php
                if ($model->step->parentId):
                    echo CHtml::link($model->step->parent->position . "." . $model->step->position, array('step/view', 'id' => $model->step->id, 'issueId' => $issue->id));
                else:
                    echo CHtml::link($model->step->position . ".0", array('step/view', 'id' => $model->step->id, 'issueId' => $issue->id));
                endif;
                echo " - " . CHtml::encode($model->step->name);
                ?>
            </div>
        </div>

        <b><?php echo Yii::t('default', 'Description') ?>:</b>
        <div class="border"><?php echo CHtml::encode($model->discrepancyDescription) ?>
            <?php if ($model->discrepancyDescriptionBy): ?>
                <div class="author"><?php echo $model->discrepancyDescriptionBy0->username ?> - <?php echo date_format(date_create($model->discrepancyDescriptionDate), 'd/m/Y') ?></div>
            <?php endif; ?>
        </div>
        <b><?php echo Yii::t('default', 'Cause Of Nonconformance') ?>:</b>
        <div class="border"><?php echo CHtml::encode($model->causeOfNonconformance) ?>
            <?php if ($model->causeOfNonconformanceBy): ?>
                <div class="author"><?php echo $model->causeOfNonconformanceBy0->username ?> - <?php echo date_format(date_create($model->causeOfNonconformanceDate), 'd/m/Y') ?></div>
            <?php endif; ?>
        </div>
        <b><?php echo Yii::t('default', 'Disposition') ?>:</b>
        <div class="border"><?php echo CHtml::encode($model->disposition) ?>
            <?php if ($model->dispositionBy): ?>
                <div class="author"><?php echo $model->dispositionBy0->username ?> - <?php echo date_format(date_create($model->dispositionDate), 'd/m/Y') ?></div>
            <?php endif; ?>

        </div>
        <b><?php echo Yii::t('default', 'Disposition Verify Note') ?>:</b>
        <div class="border"><?php echo CHtml::encode($model->dispositionVerifyNote) ?>
            <?php if ($model->dispositionVerifyNoteBy): ?>
                <div class="author"><?php echo $model->dispositionVerifyNoteBy0->username ?> - <?php echo date_format(date_create($model->dispositionVerifyNoteDate), 'd/m/Y') ?></div>
            <?php endif; ?>
        </div>
        <b><?php echo Yii::t('default', 'Corrective Action To Prevent Recurrence') ?>:</b>
        <div class="border"><?php echo CHtml::encode($model->correctiveActionToPreventRecurrence) ?>
            <?php if ($model->correctiveActionToPreventRecurrenceBy): ?>
                <div class="author"><?php echo $model->correctiveActionToPreventRecurrenceBy0->username ?> - <?php echo date_format(date_create($model->correctiveActionToPreventRecurrenceDate), 'd/m/Y') ?></div>
            <?php endif; ?>

        </div>
        <b><?php echo Yii::t('default', 'Corrective Action Verify Note') ?>:</b>
        <div class="border"><?php echo CHtml::encode($model->correctiveActionVerifyNote) ?>
            <?php if ($model->correctiveActionVerifyNoteBy): ?>
                <div class="author"><?php echo $model->correctiveActionVerifyNoteBy0->username ?> - <?php echo date_format(date_create($model->correctiveActionVerifyNoteDate), 'd/m/Y') ?></div>
            <?php endif; ?>
        </div>
        <b><?php echo Yii::t('default', 'Identified Problem Area') ?>:</b>
        <div class="radio border">
            <?php echo CHtml::activeRadioButtonList($model, 'identifiedProblemArea', $model->getAreaOptions(), array('disabled' => true, 'separator' => " | ")); ?>
        </div>
        <b><?php echo Yii::t('default', 'Closeout Note') ?>:</b>
        <div class="border">
            <?php echo CHtml::encode($model->closeoutNote); ?>
        </div>
        <div class="review">
            <div class="little left">
                <b><?php echo Yii::t('default', 'Reviewed By') ?>:</b>
                <div class="border">
                    <?php if ($model->reviewedBy): echo $model->reviewedBy0->username;
                    endif; ?>
                </div>
            </div>
            <div class="little right">
                <b><?php echo Yii::t('default', 'Reviewed Date') ?>:</b>
                <div class="border">
<?php if ($model->reviewedDate): echo date_format(date_create($model->reviewedDate), 'd/m/Y');
endif; ?>
                </div>
            </div>
        </div>
        <br clear='left'>
    </div>
</div>