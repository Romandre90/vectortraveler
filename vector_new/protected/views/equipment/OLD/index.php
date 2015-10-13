<?php
/* @var $this EquipmentController */
/* @var $dataProvider CActiveDataProvider */

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('treeview');
$cs->registerScript('test', "jQuery(\"#treeview\").treeview({});");
$cs->registerCssFile($cs->getCoreScriptUrl() . '/treeview/jquery.treeview.css');

$this->breadcrumbs = array(
    Yii::t('default', 'Equipments'),
);

$this->menu = array(
    array('label' => Yii::t('default', 'Create Equipment'), 'url' => array('create'), 'visible' => Yii::app()->user->getState('role') > 1),
    array('label' => Yii::t('default', 'View Assembly'), 'url' => array('assembly')),
);
?>

<h1><?php echo Yii::t('default', 'Equipments') ?></h1>

<ul id='treeview' class='filetree'>
    <?php
    $ul = "";
    $projectId = 0;
    $componentId = 0;
    foreach ($query as $q):
        $pname = $q['pname'];
        $pid = $q['pid'];
        $cname = $q['cname'];
        $cid = $q['cid'];
        $ename = $q['ename'];
        $eid = $q['eid'];
        $project = Project::model()->findByPk($pid);
        $component = Components::model()->findByPk($cid);
        $travelers = Equipment::model()->findByPk($eid)->travelers;
        if ($projectId != $pid):;
            $projectId = $pid;
            $componentId = 0;
            $status = 0;
            echo "$ul$ul";
            $ul = "";
            if ($project->hide) {
                $style = "";
            } else {
                $style = "style='display:none'";
            }
            ?>
            <li class="p<?php echo $pid ?>" <?php echo $style ?>><?php echo "<span class='folder'>" . $pname . "</span>"; ?>
                <ul>
                <?php endif; ?>
                <?php if ($componentId != $cid): ?>
                    <?php
                    echo "$ul";
                    $ul = "";
                    if ($component->hide) {
                        $style = "";
                    } else {
                        $style = "style='display:none'";
                    }
                    ?>
                    <li class="c<?php echo $cid ?>" <?php echo $style ?>><?php echo "<span class='folder'>" . $cname . "</span>"; ?>
                        <ul>
                        <?php endif; ?>


                        <li><span class='equipment'><?php echo CHtml::link($ename, array('view', 'id' => $eid)) ?></span>
                            <?php
                            if ($travelers) {
                                echo "<ul>";
                                foreach ($travelers as $traveler):
                                    $issue = Issue::model()->getIssueByPkFk($traveler->id, $eid);
                                    $issueId = $issue->id;
                                    
                                    ?>
                                <li><span class='file'><?php echo CHtml::link(CHtml::encode($traveler->name) . " (v$traveler->revision)", array('issue/view', 'id' => $issueId)) ; ?></span></li>
                                    <?php
                                endforeach;
                                echo "</ul>";
                            }
                            ?>
                </li>
                <?php
                $componentId = $cid;
                $ul = "</ul></li>";
                ?>
                <?php
            endforeach;
            echo "$ul$ul</ul>";
            ?>