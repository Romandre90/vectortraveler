<?php
/* @var $this EquipmentController */
/* @var $dataProvider CActiveDataProvider */

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('treeview');
$cs->registerScript('test', "jQuery(\"#treeview\").treeview({});");
$cs->registerCssFile($cs->getCoreScriptUrl() . '/treeview/jquery.treeview.css');



$this->menu = array(
    array('label' => Yii::t('default', 'Create Equipment'), 'url' => array('create'), 'visible' => Yii::app()->user->getState('role') > 1),
    //array('label' => Yii::t('default', 'View Assembly'), 'url' => array('assembly')),
);
?>


<h1><?php echo Yii::t('default', 'Issued traveler templates') ?></h1>


<ul id='treeview' class='filetree'>
    <?php
    $ul = "";
    $projectId = 0;
    $componentId = 0;
	$userId=Yii::app()->user->id;
	if(!isset($userId)) $userId=0;
    foreach ($query as $q):
        $pname = $q['pname'];
        $pid = $q['pid'];
        $cname = $q['cname'];
        $cid = $q['cid'];
        $ename = $q['ename'];
        $eid = $q['eid'];
 		$estatus=$q['estatus'];
        $project = Project::model()->findByPk($pid);
        $component = Components::model()->findByPk($cid);
        $travelers = Equipment::model()->findByPk($eid)->travelers;
        if ($projectId != $pid):;
            $projectId = $pid;
            $componentId = 0;
            $status = 0;
            echo "$ul$ul";//solo crea un espacio al principio del arbol
            $ul = "";
            if ($project->hide) {//simplemente hace invisible lo que ha de ser en el momento de cargar la página
               $style = "";
            } else {
                $style = "style='display:none'";
            }
            ?>
            <li class="p<?php echo $pid ?>" <?php echo $style ?>><?php echo "<span class='project' title='Project'>" . $pname . "</span>"; ?>
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
                    <li class="c<?php echo $cid ?>" <?php echo $style ?>><?php echo "<span class='component' title='Component'>" . $cname . "</span>"; ?>
                        <ul>
                        <?php endif; ?>

						<?php if( $estatus =="0")
                        	echo " <li><span class='equipment' title='Equipment'>". CHtml::link($ename /*. ' --closed--'*/, array('view', 'id' => $eid), array('class'=>'green'),array('onclick'=>'mostra_loading_screen()'))."</span>";
                         else
                        	echo " <li><span class='equipment' title='Equipment'>". CHtml::link($ename, array('view', 'id' => $eid),array('onclick'=>'mostra_loading_screen()'))."</span>";
                          
                            if ($travelers) {
                                echo "<ul>";
								//echo date("h:i:s:u");
                                foreach ($travelers as $traveler):
                                    $issue = Issue::model()->getIssueByPkFk($traveler->id, $eid);
                                    $issueId = $issue->id;
                                    // $progress = $issue->progressText; AND <div id="progreso" onclick='loadXMLDoc(<?php echo $issueId
									$opendocs=Opendocs::model()->find("userId!=$userId and issueId=$issueId and createTime > DATE_SUB(NOW(), INTERVAL 1 DAY)");//Mysql
									?>
									
									<li><span class='issue' title='Issued template'>
									<?php 
									if(isset($opendocs))
										echo CHtml::link(CHtml::encode($traveler->name) . " (v$traveler->revision)" , array('issue/view', 'id' => $issueId), array('class'=>'red'), array('onclick'=>'mostra_loading_screen()')); 
									else if($issue->status==0)
                                    	echo CHtml::link(CHtml::encode($traveler->name) . " (v$traveler->revision)", array('issue/view', 'id' => $issueId), array('class'=>'green'), array('onclick'=>'mostra_loading_screen()'));
                                    else
                                    	echo CHtml::link(CHtml::encode($traveler->name) . " (v$traveler->revision)", array('issue/view', 'id' => $issueId), array('onclick'=>'mostra_loading_screen()'));
									?></span></li><?php
									
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