<?php 
$i = 1;
foreach($steps as $step): $si= 1;?>
<h2>
    <?php if(!$step->parent)echo '<a href="#"><img src="../../images/top.gif"></a> '; echo CHtml::link($i.".0", array('step/view', 'id'=>$step->id))." ".CHtml::encode($step->name);?>
</h2>
<ul>
            <div id='elements'>
            <?php
        $this->renderPartial('../step/_elements', array(
            'elements' => $step->elements,
        ));?></div>
    
<?php foreach($step->steps as $subStep):?>
    <li><?php echo CHtml::link($i.".$si", array('step/view', 'id'=>$subStep->id))." ".nl2br(CHtml::encode($subStep->name));$si++; ?></li>
    <div id='elements'>
            <?php
        $this->renderPartial('../step/_elements', array(
            'elements' => $subStep->elements,
        ));
        ?></div>
    <?php
        $this->renderPartial('../step/_comments', array(
            'comments' => $subStep->comments,
        ));
        ?>
   
<?php endforeach;  ?>
     
</ul>
<?php
        $this->renderPartial('../step/_comments', array(
            'comments' => $step->comments,
        ));
        ?>
<?php $i++; ?>
<?php endforeach; ?>