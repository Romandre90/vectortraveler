<?php
/* @var $this StepController */
/* @var $model Step */
Yii::app()->clientScript->registerScript('settings-script', <<<EOD
    $(".view :input").attr("disabled", true);
EOD
);
$linkDiscrepancy = "";
$menu = true;
if ($issue) {
    $issueId = $issue->id;
    $discrepancy = $model->getDiscrepancy($issueId);
    if($discrepancy)
    $linkDiscrepancy = CHtml::link(CHtml::image('/images/warning.png'),array('nonconformity/view','id'=>$discrepancy->id));
    
    $status = $issue->status;
    if ($model->parentId):
        $this->breadcrumbs = array(
            Yii::t('default', 'Equipments') => array('equipment/index'),
            $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
            $issue->traveler->name => array('issue/view', 'id' => $issue->id),
            Yii::t('default', 'Step') . ' ' . $model->parent->position . ".0" => array('view', 'id' => $model->parentId, 'issueId' => $issueId),
            Yii::t('default', 'Step') . ' ' . $model->parent->position . "." . $model->position,);
    else:
        $this->breadcrumbs = array(
            Yii::t('default', 'Equipments') => array('equipment/index'),
            $issue->equipment->identifier => array('equipment/view', 'id' => $issue->equipmentId),
            $issue->traveler->name => array('issue/view', 'id' => $issue->id),
            Yii::t('default', 'Step') . ' ' . $model->position . ".0",
        );
    endif;
    if ($status == 0) {
        $menu = false;
    }
} else {
    $discrepancy = false;
    $issueId = null;
    $status = false;
    if ($model->parentId):
        $this->breadcrumbs = array(
            Yii::t('default', 'Travelers') => array('traveler/index'),
            $model->travelerId => array('traveler/view', 'id' => $model->travelerId),
            Yii::t('default', 'Step') . ' ' . $model->parent->position . ".0" => array('view', 'id' => $model->parentId),
            Yii::t('default', 'Step') . ' ' . $model->parent->position . "." . $model->position,);
    else:
        $this->breadcrumbs = array(
            Yii::t('default', 'Travelers') => array('traveler/index'),
            $model->travelerId => array('traveler/view', 'id' => $model->travelerId),
            Yii::t('default', 'Step') . ' ' . $model->position . ".0",
        );
    endif;
}



if ($menu) {
    if ($discrepancy) {
        $this->menu = array(
            array('label' => Yii::t('default', 'View Nonconformity'), 'url' => array('nonconformity/view', 'id' => $discrepancy->id), 'visible' => Yii::app()->user->getState('role') > -1),
            array('label' => Yii::t('default', 'Update Step'), 'url' => array('update', 'id' => $model->id), 'visible' => $model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId)),
            array('label' => Yii::t('default', 'Reorder Steps'), 'url' => array('reorder','id' => $model->id),'visible'=>$model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId) && $model->stepCount > 1),
            array('label' => Yii::t('default', 'Delete Step'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this step?')), 'visible' => $model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId)),
            );
    } else {
        $this->menu = array(
            array('label' => Yii::t('default', 'Create Nonconformity Report'), 'url' => array('nonconformity/create', 'stepId' => $model->id, 'issueId' => $issueId), 'visible' => $status && Yii::app()->user->getState('role') > -1),
            array('label' => Yii::t('default', 'Update Step'), 'url' => array('update', 'id' => $model->id), 'visible' => $model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId)),
            array('label' => Yii::t('default','Reorder Steps'), 'url' => array('reorder','id' => $model->id),'visible'=>$model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId) && $model->stepCount > 1),
            array('label' => Yii::t('default', 'Delete Step'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this step?')), 'visible' => $model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId)),
        );
    }
}
?>
<div class="view">
<?php if ($model->parentId): ?>
        <h3><?php echo $linkDiscrepancy." ". $model->parent->position . "." . $model->position . " " . CHtml::encode($model->name) ?></h3>
        <?php echo "<div class='description'>".nl2br (CHtml::encode($model->description))."</div>"; ?>
    <?php else: ?>
        <h2><?php echo $linkDiscrepancy." ". "$model->position.0 " . CHtml::encode($model->name); ?></h2>
        <?php echo "<div class='description'>".nl2br (CHtml::encode($model->description))."</div>"; ?>
    <?php
    endif;
    echo $this->renderPartial('_elements', array('elements' => $model->elements, 'issueId' => $issueId,));
    $array = array();
    $i = 1;
    foreach ($model->steps as $subStep):
        ?>
        <div class="view">
<?php
            $subdiscrepancy = $subStep->getDiscrepancy($issueId);
    $linksubDiscrepancy = "";
    if ($subdiscrepancy)
        $linksubDiscrepancy = CHtml::link(CHtml::image('/images/warning.png'),array('nonconformity/view','id'=>$subdiscrepancy->id));
        ?>
        <?php echo $linksubDiscrepancy." ".CHtml::link("$model->position.$subStep->position", array('step/view', 'id' => $subStep->id, 'issueId' => $issueId)) . " " . CHtml::encode($subStep->name); ?>
            <?php echo "<div class='description'>".nl2br(CHtml::encode($subStep->description))."</div>"; ?>
            <?php echo $this->renderPartial('_elements', array('elements' => $subStep->elements, 'issueId' => $issueId, 'sub'=> true)); ?>
        </div>
            <?php
            $this->renderPartial('_comments', array(
                'comments' => $subStep->getComment($issueId),
            ));
            ?>
    <?php endforeach; ?>
</div>
    <?php
    $this->renderPartial('_comments', array(
        'comments' => $model->getComment($issueId),
    ));
    ?>
<?php
$options = false;
if (Yii::app()->user->getState('role') < 1) {
    if ($status == 0) {
        $options = false;
    } else {
        $options = array(Yii::t('default', 'Comment') => $this->renderPartial('/comment/_form', array('model' => $comment), $this),);
    }
} elseif ($model->traveler->status == 1 && (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId)) {
    if ($model->parent) {
        $options = array(
            Yii::t('default', 'Element') => $this->renderPartial('/element/_form', array('model' => $element), $this),
            Yii::t('default', 'Table') => $this->renderPartial('/element/_grille', array('model' => $element), $this),
            Yii::t('default', 'File') => $this->renderPartial('/file/_form', array('model' => $file), $this),
            Yii::t('default', 'Link') => $this->renderPartial('_link', array('model' => $link), $this),
            Yii::t('default', 'Text') => $this->renderPartial('_text', array('model' => $text), $this),
        );
    } else {
        $options = array(
            Yii::t('default', 'Substep') => $this->renderPartial('_form', array('model' => $subStepModel, 'travelerId' => $model->travelerId, 'parentId' => $model->id), $this),
            Yii::t('default', 'Element') => $this->renderPartial('/element/_form', array('model' => $element), $this),
            Yii::t('default', 'Table') => $this->renderPartial('/element/_grille', array('model' => $element), $this),
            Yii::t('default', 'File') => $this->renderPartial('/file/_form', array('model' => $file), $this),
            Yii::t('default', 'Link') => $this->renderPartial('_link', array('model' => $link), $this),
            Yii::t('default', 'Text') => $this->renderPartial('_text', array('model' => $text), $this),
        );
    }
} else {
    if ($issue) {
        if ($status == 0) {
            $options = false;
        } else {
            $options = array(Yii::t('default', 'Comment') => $this->renderPartial('/comment/_form', array('model' => $comment), $this),);
        }
    }
}
if (is_array($options) && !Yii::app()->user->isGuest) {
    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => $options,
        // additional javascript options for the tabs plugin
        'options' => array(
            'collapsible' => true,
        ),
    ));
}
?>