<?php
if (!isset($issueId)) {
    $issueId = null;
}

$i = 1;
?>
<div class="view">
<?php
foreach ($steps as $step) {
    if (!$step->parent)
        if($step->haveDiscrepancy($issueId))
            echo CHtml::image(Yii::app()->request->baseUrl.'/images/warning.png');
        	echo "<a href='#$step->id'>$step->position.0 $step->name</a> " . $step->getProgressText($issueId) . "<br/>";
		}
if(isset($save)){
    echo "<a href='#save'>".Yii::t('default',$save)."</a>";
}else{
    $save=false;
};
?>
</div>
<?php foreach ($steps as $step): $si = 1;
    $class = "";
    if ($issueId) {
        $progress = $step->getProgress($issueId);
        if (!is_null($progress)) {
            if ($progress < 50) {
                $class = 'empty';
            } elseif ($progress < 100) {
                $class = 'half';
            } else {
                $class = 'ok';
            }
        }
    }
    ?>
    <div class="view <?php echo $class ?>">
        <h2>
            <?php 
            $discrepancy = $step->getDiscrepancy($issueId);
            
            if (!$step->parent)
                echo "<a href='#' id='$step->id'><img src='".Yii::app()->request->baseUrl."/images/top.gif'></a> ";
            if($discrepancy)
                echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/warning.png'),array('nonconformity/view','id'=>$discrepancy->id));
            if($save){
                echo $i.".0 ". CHtml::encode($step->name);
            }else
            echo CHtml::link($i . ".0", array('step/view', 'id' => $step->id, 'issueId' => $issueId)) . " " . CHtml::encode($step->name);
            
            ?>
        </h2>
        <div class='description'>
             <?php echo nl2br(CHtml::encode($step->description)); ?>
        </div>

        <div id='elements'>
            <?php 
            $this->renderPartial('../step/_elements', array(
                'elements' => $step->elements,
                'issueId' => $issueId,
            ));
            ?></div>

        <?php  foreach ($step->steps as $subStep): ?>
            <div class="view"><?php
                $subdiscrepancy = $subStep->getDiscrepancy($issueId);
            if($subdiscrepancy)
                echo CHtml::link(CHtml::image('../../images/warning.png'),array('nonconformity/view','id'=>$subdiscrepancy->id));?>
                <?php if($save){ echo $i. ".$si ". CHtml::encode($subStep->name);}else echo CHtml::link($i . ".$si", array('step/view', 'id' => $subStep->id, 'issueId' => $issueId)) . " " . CHtml::encode($subStep->name);
                $si++;
                ?>
                <div class='description'>
             <?php  echo nl2br(CHtml::encode($subStep->description));?>
        </div>


                <div id='elements'>
                    <?php
                  /* echo $issueId . " ";
                   for( $AAA=0; $AAA<1000; $AAA++ )
                   {
                   		echo $AAA." ";
                   } */
                    $this->renderPartial('../step/_elements', array(
                        'elements' => $subStep->elements,
                        'issueId' => $issueId,
                    ));
                    ?></div>
                
            </div>
       			 <?php
                $this->renderPartial('../step/_comments', array(
                    'comments' => $subStep->getComment($issueId),
                ));
                ?>
    <?php endforeach; ?>
    </div>


    <?php
    $this->renderPartial('../step/_comments', array(
        'comments' => $step->getComment($issueId),
    ));
    ?>
    <?php $i++; ?>
<?php endforeach; ?>