<?php
/* @var $this StepController */
/* @var $model Step */
Yii::app()->clientScript->registerScript('settings-script', <<<EOD
    $(".view :input").attr("disabled", true);
	$(".view :input.Excel").attr("disabled", false);
EOD
);

$linkDiscrepancy = "";
$menu = true;
//if this is an issued traveler step!
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
} else { //else is a traveler's step
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
			//array('label' => Yii::t('default', 'Importar Excel'), 'url' => array('importExcelTable', 'id' => $model->id, 'issueId'=>$issueId), 'visible' => $status && Yii::app()->user->getState('role') > -1),
            array('label' => Yii::t('default','Reorder Steps'), 'url' => array('reorder','id' => $model->id),'visible'=>$model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId) && $model->stepCount > 1),
            array('label' => Yii::t('default', 'Delete Step'), 'url' => '#', 'linkOptions' => array('class'=>'del','submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this step?')), 'visible' => $model->traveler->status == 1 &&  (Yii::app()->user->getState('role')>2 OR Yii::app()->user->id == $model->traveler->userId)),
        );
    }
}
?>
<div class="view">
<?php 

//import Excel files on issues
/* This allows tables checkbox being complete deleted*/


if(!$issue && $model->traveler->status==1){
echo "<script type='text/javascript'>
		function toggle_visibility(traceability){
				var e=document.getElementsByClassName('excel');
				
				for (var i=0; i<e.length; i++){
				if(e[i].style.display=='inline'){ 
					e[i].style.display='none';
				}
				else 	e[i].style.display='inline';
			}
			}
		</script>";

// "Import excel file" code
	if(Yii::app()->user->id == $model->traveler->userId){
		echo "<div style='text-align:right; '><input class='Excel' type=button onclick=toggle_visibility(&#39;excel&#39;) value='Upload Excel Table'></div>";
		echo "<div class='excel' style='display: none;  '>";
		$form =$this->beginWidget(
				'CActiveForm',
				array(
						'id'=>'multipart/form-data',
						'enableAjaxValidation'=>false,
						'htmlOptions'=>array('enctype'=>'multipart/form-data',
											'class'=>'Excel',
											'name'=>'Excel',
											'style'=> 'background-color: rgb(190,256,190)'),
						'action'=>array('importExcelTableTraveler', 'id' => $model->id),
						'clientOptions' => array(
							'validateOnSubmit'=>true,
							),
						)
		);
		echo "- Excel file must be a table with a maximum of 6 columns</br>";
		echo "- It will only read columns and rows labels, other cells will be ignored";
		echo "</br>- <b>Only *.xlsx files</b></br>";
		//echo CHtml::fileField("File", 'File', array( 'class'=>'File'));
		echo $form->fileField($model,'Excel', array('class'=>'Excel', 'name'=>'Excel', 'id'=>'Excel'));
		echo $form->error($model,'Excel', array('clientValidation'=>'js:customValidateFile(messages)'),false,true);
		$infoFieldFile=(end($form->attributes));
		//echo CHtml::submitButton('Submit','action'=>array('importExcelTable', 'id' => $model->id, 'issueId'=>$issueId));
		echo CHtml::submitButton('Submit', array('class'=>'Excel'));
		$this->endWidget();
		echo "</div>";
	}

	
?>
	<script>
			function customValidateFile(messages){
				var control = $(nameC).get(0);
				
				if(control.files.length==0){
					messages.push("File is required");
					return false;
				}
				
				file = controlfiles[0];
				
				if (excel.name.substr((file.name.lastIndexOf('.') +1)) !='xlsx'){
					messages.push("This is not a xlsx file");
					return false;
				}
			}
	</script>

	
<?php
	}
if ($model->parentId): ?>
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
        <?php 
		echo $linksubDiscrepancy." ".CHtml::link("$model->position.$subStep->position", array('step/view', 'id' => $subStep->id, 'issueId' => $issueId)) . " " . CHtml::encode($subStep->name); ?>
            <?php echo "<div class='description'>".nl2br(CHtml::encode($subStep->description))."</div>"; ?>
            <?php echo $this->renderPartial('_elements', array('elements' => $subStep->elements, 'issueId' => $issueId, 'sub'=> true)); 
					?>
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
            //Yii::t('default', 'Excel') => $this->renderPartial('/step/_excel', array('model' => $model->elements), $this),
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
