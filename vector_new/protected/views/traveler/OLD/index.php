<?php
/* @var $this TravelerController */
/* @var $dataProvider CActiveDataProvider */
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('treeview');
$cs->registerScript('test', "jQuery(\"#treeview\").treeview({});");
$cs->registerCssFile($cs->getCoreScriptUrl() . '/treeview/jquery.treeview.css');

$this->breadcrumbs = array(
    Yii::t('default', 'Travelers'),
);

$this->menu = array(
    array('label' => Yii::t('default', 'Search Travelers'), 'url' => array('admin')),
    array('label' => Yii::t('default', 'Create Traveler'), 'url' => array('create'), 'visible' => Yii::app()->user->getState('role') > 1),
);
?>

<h1><?php echo Yii::t('default', 'Travelers') ?></h1>
<ul id='treeview' class='filetree'>
    <?php
    $ul = "";
    $projectId = 0;
    $componentId = 0;
    $workId = 0;
    $archive = "";
    foreach ($query as $q):
        $pname = $q['pname'];
        $pid = $q['pid'];
        $cname = $q['cname'];
        $cid = $q['cid'];
        $tname = $q['tname'];
        $tid = $q['tid'];
        $project = Project::model()->findByPk($pid);
        $component = Components::model()->findByPk($cid);
        $traveler = Traveler::model()->findByPk($tid);
        if ($projectId != $pid):;
            $componentId = 0;
            $workId = 0;
            $status = 0;
            if(Preference::model()->hideArchive){
                $styleA = "";
            }  else {
                $styleA = "style='display:none'";
            }
            if ($project->hide) {
                $style = "";
            } else {
                $style = "style='display:none'";
            }
            ?>
            <?php
            echo "$archive$ul$ul$ul";
            $ul = "";
            $archive = "";
            ?>
            <li class="p<?php echo $pid ?>" <?php echo $style ?>><?php echo "<span class='folder'>" . $pname . "</span>"; ?>
                <ul>
                <?php endif; ?>
                <?php if ($componentId != $cid): ?>
                    <?php
                    echo "$archive$ul$ul";
                    $ul = "";
                    $archive = "";
                    if ($component->hide) {
                        $style = "";
                    } else {
                        $style = "style='display:none'";
                    }
                    ?>
                    <li class="c<?php echo $cid ?>" <?php echo $style ?>><?php echo "<span class='folder'>" . $cname . "</span>"; ?>
                        <ul>
                        <?php endif; ?>
                        <?php if ($traveler->getNode($workId)): ?>
                            <?php echo "$archive$ul" ?>
                            <li><?php echo "<span class='folder'>" . trim(CHtml::encode($tname)) . "</span>"; ?>
                                <ul>
                                <?php endif; ?>
                                <?php
                                if ($traveler->status == 2 && $status != $traveler->status) {
                                    echo "<li class='archive' $styleA><span class='folder'>" . Yii::t('default', 'Archive') . "</span><ul>";
                                }
                                ?>
                                <li><span class='file'><?php echo CHtml::link("(v" . $traveler->revision . ") " . $traveler->statusText, array('view', 'id' => $traveler->id)) ?></span></li>
                                <?php
                                $projectId = $pid;
                                $componentId = $cid;
                                if (is_null($traveler->parentId)) {
                                    $workId = $traveler->id;
                                } else {
                                    $workId = $traveler->parentId;
                                }
                                $status = $traveler->status;
                                $ul = "</ul></li>";
                                if ($status == 2) {
                                    $archive = "$ul";
                                } else {
                                    $archive = "";
                                }
                                ?>
                                <?php
                            endforeach;
                            echo "$ul$ul$ul</ul>";
                            ?>
                                