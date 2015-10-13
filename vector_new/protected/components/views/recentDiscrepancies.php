<ul>
    
    <?php 
    $recentDiscrepancies = $this->getOpenDiscrepancies();
    if($recentDiscrepancies){
       foreach ($recentDiscrepancies as $discrepancy): ?>
        <b><?php echo $discrepancy->originator->username; ?></b> - <?php echo Yii::t('default','posted').' '.Yii::t('default','{elapsedTime} ago',array('{elapsedTime}'=>$discrepancy->timeElapsed));?>
        <div>
            <?php echo CHtml::link(CHtml::encode($discrepancy->equipment->concatenateIdentity." / ".$discrepancy->description), array('nonconformity/view', 'id' => $discrepancy->id));
            ?>
        </div>
        <?php endforeach; 
    }else{
        echo Yii::t('default','No Report of Nonconformities Open');
    } 
    ?>
</ul>