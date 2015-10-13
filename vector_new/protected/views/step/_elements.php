<?php
/* This allows tables checkbox being complete deleted*/

Yii::app()->clientScript->registerScript('hideImage', <<<EOD
		function hideImage(id){
			e=document.getElementsByClassName(id);
			for (var i=0; i<e.length; i++){
				if(e[i].style.display=='inline'){ 
				e[i].style.display='none';} else if (e[i].style.display=='none'){e[i].style.display= 'inline';}
			}
		};
EOD
,CClientScript::POS_BEGIN
);


echo "<div style='display:none;'><table class='table'><th><input name='Table[xxx][x][x]' type='text' value=' '></th></table></div>";
echo "<div style='display:none;'><input type='chekbox' class='check'><input name='Checks[xxx][x][x]' type='text' value=' '></input></div>";

$items = false;
if ($this->uniqueid == 'issue') {
    $issueView = true;
} else {
    $issueView = false;
}
$info=true;
foreach ($elements as $element):
    if (!isset($issueId)) {
        $issueId = null;
    }
    $titre = "";
    if ($element->label && $element->typeId <> 11)
        $titre = "<b>" . CHtml::encode($element->label) . "</b><br/>";
    if ($element->help)
        $titre .="<i>" . CHtml::encode($element->help) . "</i><br/>";

    if ($this->uniqueid == "step" && 
	$element->step->traveler->status == 1 && 
	(Yii::app()->user->getState('role') > 2 OR 
	Yii::app()->user->id == $element->step->traveler->userId) 
	&& !isset($sub)) {
        $titre = "<div class='sortable'><div class='droite'>" . CHtml::link(Yii::t('default', 'edit'), array('element/update', 'id' => $element->id)) . ' | ' . CHtml::link(Yii::t('default', 'delete'), "#", array('class' => 'red', 'submit' => array('element/delete', 'id' => $element->id), 'confirm' => Yii::t('default', 'Are you sure you want to delete this element?'))) . "</div>" . $titre;
        switch ($element->typeId) {
            case 0:
                $items[$element->id] = $titre . CHtml::textField("Result[elementid][$element->id]") . "</div>";
                break;
            case 1:
                $items[$element->id] = $titre . CHtml::textArea("Result[elementid][$element->id]") . "</div>";
                break;
            case 5:
                $items[$element->id] = $titre . CHtml::radioButtonList("Result[elementid][$element->id]", '', CHtml::listData($element->values, 'id', 'value')) . "</div>";
                break;
            case 6:
                $items[$element->id] = $titre . CHtml::checkBoxList("Checks[elementid][$element->id]", '', CHtml::listData($element->values, 'id', 'value')) . "</div>";
                break;
            case 7:
                $items[$element->id] = $titre . CHtml::dropDownList("Result[elementid][$element->id]", '', CHtml::listData($element->values, 'id', 'value'), array('empty' => '')) . "</div>";
                break;
            case 3:
                $items[$element->id] = $titre . CHtml::urlField("Result[elementid][$element->id]") . "</div>";
                break;
            case 2:
                $items[$element->id] = $titre . CHtml::fileField("File[elementid][$element->id]") . "</div>";
                break;
            case 8:
                $items[$element->id] = $titre . CHtml::fileField("File[elementid][$element->id]") . "</div>";
                break;
            case 4:
                $items[$element->id] = $titre . CHtml::textField("Result[elementid][$element->id]") . "</div>";
                break;
            case 10:
                $items[$element->id] = $titre .strip_tags($element->text, '<sub><sup><b><i><u><strike><br><p>'). "</div>";
                break;
            case 11:
                $titre .= "<div class='link center'>";
                if ($element->label) {
                    $label = $element->label;
                } else {
                    $label = $element->url;
                }
                $items[$element->id] = $titre . CHtml::link($label, $element->url, array('target' => '_blank')) . "</div></div>";
                break;
            case 12:
                $file = $element->file;
				if(isset($file)){
					$titre .= "<div class='file center'>";
					$description = "<div class='fileInfo'><b>" . CHtml::encode($file->name) . "</b><br>" . CHtml::encode($file->description) . "</div>";
					if ($file->image) {
						$items[$element->id] = $titre . CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/files/' . $file->link, "image", array("max-width" => 600)), Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank')) . "$description</div></div>";
					} else {
						$items[$element->id] = $titre . CHtml::link($file->fileSelected, Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank')) . "$description</div></div>";
					}
				}else echo '<a class="red">There is a missing file  in the database!! Please contact the administrator.</a></br>';

                break;
            case 13:
              
				$table = $titre;
                $columns = "<td></td>";
                $rows = "";
                $i = 0;
				$r=0;
                foreach ($element->columns as $column) {
                    $columns .= '<th>' . $column->value . '</th>';
                    $i++;
                }
                foreach ($element->rows as $row) {
                    $rows .='<tr><th>' . $row->value . '</th>';
                    for ($index = 0; $index < $i; $index++) {
						$res = $element->getResultTable($issueId, $index, $r);//issueId, column, row
                        $rows .= '<td><input type="text">'.$res.'</td>';
                    }
                    $rows .="</tr>";
                }
                $items[$element->id] = $titre . "<table class='table'><tr>$columns</tr>$rows</table>";
                break;
            case 14:
                $table = $titre;
                $columns = "<td></td>";
                $rows = "";
                $i = 0;
                foreach ($element->columns as $column) {
                    $columns .= '<th>' . $column->value . '</th>';
                    $i++;
                }
                foreach ($element->rows as $row) {
                    $rows .='<tr><th>' . $row->value . '</th>';
                    for ($index = 0; $index < $i; $index++) {
                        $rows .= '<td><input type="radio" value="on">Yes<input type="radio" value="off">No</td>';
                    }
                    $rows .="</tr>";
                }
                $items[$element->id] = $titre . "<table class='table'><tr>$columns</tr>$rows</table>";
                break;
        }
    } else {
        echo "<ul><div class='element'><li>$titre";
        switch ($element->typeId) {
            case 10:
                echo strip_tags($element->text, '<sub><sup><b><i><u><strike><br><p>');
                break;
            //case 11:
                echo "<div class='link center'>";
                if ($element->label) {
                    $label = $element->label;
                } else {
                    $label = $element->url;
                }
                echo CHtml::link($label, $element->url, array('target' => '_blank')) . "</div>";
                break;
            case 8:
                $files = $element->getFiles($issueId);
                if ($files) {
                    $ok = false;
                    if ($issueId && $this->uniqueid == "result") {
                        $this->widget('CMultiFileUpload', array(
                            'name' => "File[elementid][$element->id]",
                            'duplicate' => 'Duplicate file!',
                        ));
                        $ok = true;
                    }
                    foreach ($files as $f) {
                        $file = $f->file;
                        echo "</br> ".CHtml::link($file->fileSelected, Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank'));
                        if($file->image==1){
							echo CHtml::ajaxLink('(Display)',Yii::app()->createUrl('/step/loadImage', array('id'=>$file->id)), array('replace'=>"#imageToLoad$file->id",'beforeSend' => "function() { $('#imageToLoad$file->id').addClass('loading');}",'complete' => "function() { $('#imageToLoad$file->id').removeClass('loading');}",),array('type'=>'POST','onClick'=>"javascript:hideImage('image[$file->link]')",'class'=>"image[$file->link]",'style'=>'color:grey',));
								
							echo "<div id='imageToLoad$file->id'></div>";
							
						}
						
						
						if ($ok) {
                            echo " - " . CHtml::ajaxLink(Yii::t('default', 'delete'), Yii::app()->createUrl('/file/delete', array('id' => $file->id, 'ajax' => 'delete')), array(
                                'type' => 'POST',
                                'success' => "function( ){
                                                  window.location.reload(true);
                                                }",
                                    )
                            );
                        }
                        //echo "<br>" . $file->user->username . ' ' . $file->dateCreated;
                        echo "<div style='background-color:rgb(220,220,220); font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>" . $file->user->username . ' ' . $file->dateCreated."</div>";
						//echo "<br>";
						
                    }
                } else {
                    $this->widget('CMultiFileUpload', array(
                        'name' => "File[elementid][$element->id]",
                        'duplicate' => 'Duplicate file!',
                    ));
                }

                break;
            case 12:
                $file = $element->file;
                echo "<div class='file center'>";
				if(isset($file)){
					if ($file->image) {
						echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/files/' . $file->link, "image", array("max-width" => 600)), Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank'));
					} else {
						echo CHtml::link($file->fileSelected, Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank'));
					}
					echo "<div class='fileInfo'><b>" . CHtml::encode($file->name) . "</b><br>" . CHtml::encode($file->description) . "</div>";
				}else echo '<a class="red">There is a missing file in the database!! Please contact the administrator.</a></br>';
                
                echo "</div>";
                break;
            case 13:
			$id = $element->id;
				$url=Yii::app()->request->baseUrl."/index.php/element/excelFile/".$id."?"."issueId=".$issueId;
				echo "<table class='table'><tr><td><input class='Excel'  type='button' value='Excel' onclick=location.href='".$url."';>";
                $i = 0;
                
                foreach ($element->columns as $column) {
                    echo '<th>' . $column->value . '</th>';
                    $i++;
                }
                echo "</tr>";
                $r = 0;


                foreach ($element->rows as $row) {
                    echo '<tr><th>' . $row->value . '</th>';
                    for ($index = 0; $index < $i; $index++) {
                        $res = $element->getResultTable($issueId, $index, $r);
						if($res!=null){
							
							echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'><input style='margin:2px;' name='Table[$id][$r][$index]' type='text' value ='$res'><br><div class='info' style='display: none; font-size:small; padding:3px; background-color:rgb(220,220,220); border-radius:4px'>" . $element->getUserDateForTables($issueId, $r, $index)."</div></td>";
						} else
							echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'><input style='margin:2px;' name='Table[$id][$r][$index]' type='text' value =' '><br><div class='info' style='display: none; font-size:small; padding:3px; background-color:rgb(220,220,220); border-radius:4px'>" . $element->getUserDateForTables($issueId, $r, $index)."</div></td>";
					}
                    echo "</tr>";
                    $r++;
                }
                echo "</table>";
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
				echo "<div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';>";
				
				break;
            case 14:
				
				$info=false;
                $i=0;
                $id = $element->id;
				
				
				$url=Yii::app()->request->baseUrl."/index.php/element/excelFileOutput14/".$id."?"."issueId=".$issueId;
				echo "<table class='table'><tr><td><input class='Excel' id='hola' type='button' value='Excel' onclick=location.href='".$url."';>";
				
				foreach ($element->columns as $column) {
                    echo '<th>' . $column->value . '</th>';
                    $i++;
                }
                echo "</tr>";
                $r = 0;
                foreach ($element->rows as $row) {
                    echo '<tr><th>' . $row->value . '</th>';
                    for ($index = 0; $index < $i; $index++) {
                        $res = $element->getResultTable($issueId, $index, $r);
						if ($res=="checked")
							echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'><input  name='Table[$id][$r][$index]' $res type='radio' value='on'>Yes<input name='Table[$id][$r][$index]'  type='radio' value='off'>No<div class='info' style='font-size:small;'><br><p class='info' style='display:none; padding:4px; border-radius:3px; background-color:rgb(220,220,220)'>" . $element->getUserDateForTables($issueId, $r, $index)."</p></div></td>";
						 
						else if($res=="off")
							echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'><input name='Table[$id][$r][$index]' type='radio' value='on'>Yes<input name='Table[$id][$r][$index]' checked  type='radio' value='off'>No<div class='info' style='font-size:small;'><br><p class='info' style='display: none; padding:4px; border-radius:3px;background-color:rgb(220,220,220);'>" . $element->getUserDateForTables($issueId, $r, $index)."</div></td>";
						else
							echo "<td><input name='Table[$id][$r][$index]' type='radio' value='on'>Yes<input name='Table[$id][$r][$index]'  type='radio' value='off'>No</td>";
					}
                    $r++;
                    echo "</tr>";
                }
                echo "</table>";
                $url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
				echo "<div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';>";
				break;
            case 0:
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
				
                if ($element->getResult($issueId) && $issueView){
                    echo "<div class='result' title='".$element->getUserDate($issueId)."'>" . $element->getResult($issueId) . "</div><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
					if($element->getUserDate($issueId)){
						echo "</br><div style='background-color:rgb(220,220,220); font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</div>";
					}
				}
                else{
                    echo "<div>".CHtml::textField("Result[elementid][$element->id]", $element->getResult($issueId), array('maxlength' => 50, 'title'=>$element->getUserDate($issueId)))."</div>";
					echo "<div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
                    if($element->getUserDate($issueId)){
						echo "<div style='background-color:rgb(220,220,220); font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</div>";
					}
                }
                break;
            case 1:
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
                if ($element->getResult($issueId) && $issueView):
					$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;
                    echo "<div class='result' title='".$element->getUserDate($issueId)."'>" . nl2br($element->getResult($issueId)) . "</div><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></br></div>";
                    echo "<div style='background-color:rgb(220,220,220); font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</div>";
					
                else:
					echo "<div title='".$element->getUserDate($issueId)."'>";
                    echo CHtml::textArea("Result[elementid][$element->id]", $element->getResult($issueId), array('maxlength' => 1500));
					echo "</div>";
					echo "<div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
					if($element->getUserDate($issueId)){
						echo "<div style='background-color:rgb(220,220,220); font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</div>";
					}
					
                endif;
                break;
                break;
            case 5:
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
				echo "<div title='".$element->getUserDate($issueId)."'>";
                echo CHtml::radioButtonList("Result[elementid][$element->id]", $element->getResult($issueId), CHtml::listData($element->values, 'id', 'value'));
                echo "</br><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
				if($element->getUserDate($issueId)){
					echo "<br><p style='background-color:rgb(220,220,220);font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</p>";
				}
				echo "</div>";
                break;
            case 6:
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
                echo CHtml::checkBoxList("Checks[elementid][$element->id]", $element->getResults($issueId), CHtml::listData($element->values, 'id', 'value'), array('labelOptions'=>array('title'=>$element->getUserForBox2($issueId))));
				echo "</br><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
                echo "<div>".$element->getUserForBox($issueId)."</div>";
				break;
            case 7:
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
				echo "<div title='".$element->getUserDate($issueId)."'>";
                echo CHtml::dropDownList("Result[elementid][$element->id]", $element->getResult($issueId), CHtml::listData($element->values, 'id', 'value'), array('empty' => ''));
                echo "</br><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
				if($element->getUserDate($issueId))
					echo "<br><br><p style='background-color:rgb(220,220,220);font-size:small; display:none ; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</p>";
				echo "</div>";
				break;
            case 3:
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;
                if ($element->getResult($issueId) && $issueView) {
                    echo "<div title='".$element->getUserDate($issueId)."'>";
					echo CHtml::link($element->getResult($issueId), $element->getResult($issueId), array('target' => '_blank'));
					echo "</br><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
					if($element->getUserDate($issueId))
						echo "</br><p style='background-color:rgb(220,220,220);font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</p>";
					echo "</div";
                } else {
				    echo "<div title='".$element->getUserDate($issueId)."'>";
                    echo CHtml::textField("Result[elementid][$element->id]", $element->getResult($issueId));
					echo "</br><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
					if($element->getUserDate($issueId))
						echo "</br><p style='background-color:rgb(220,220,220);font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</p>";
					echo "</div";
                }
				
                break;
            case 2:
                $file = $element->getFile($issueId);
                if ($file) {
                    if ($file->image) {
                        echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/files/' . $file->link), Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank'));
                    } else {
                        echo CHtml::link($file->fileSelected, Yii::app()->request->baseUrl . '/files/' . $file->link, array('target' => '_blank'));
                    }
                    if ($issueId && $this->uniqueid == "result") {
                        echo " - " . CHtml::ajaxLink(Yii::t('default', 'delete'), Yii::app()->createUrl('/file/delete', array('id' => $file->id, 'ajax' => 'delete')), array('type' => 'POST','success' => "function( ){window.location.reload(true);}",));
                    }
					
                    echo "<br><p style='background-color:rgb(220,220,220); display:none; font-size:small; padding:4px; border-radius:3px;' class='info'>" . $element->getUserDate($issueId)."</p>";
                } else {
                    echo CHtml::fileField("File[elementid][$element->id]");
                }
                break;
            case 4:
				echo "<div title='".$element->getUserDate($issueId)."'>";
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => "Result[elementid][$element->id]",
                    'value' => $element->getResult($issueId),
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'HH:mm dd/mm/yy'
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;',
                        'readonly' => true,
                    ),
                ));
				$url=Yii::app()->request->baseUrl."/index.php/step/excelComp/".$issueId."?"."elementId=".$element->id;//funciona
                echo "</div><div class='exceltools' style='display:none'><input class='Excel'  type='button' value='Excel Chart' onclick=location.href='".$url."';></div>";
				if($element->getUserDate($issueId)){
					echo "</br><p style='background-color:rgb(220,220,220);font-size:small; display:none; padding:4px; border-radius:3px;' class='info'>".$element->getUserDate($issueId)."</p>";
                }
				break;
        }
        echo "</li></div></ul>";
    }
endforeach;
?>

<?php

if ($items)
    $this->widget('zii.widgets.jui.CJuiSortable', array(
        'items' => $items,
        // additional javascript options for the JUI Sortable plugin
        'options' => array(
            'placeholder' => 'ui-state-highlight',
            'forcePlaceholderSize' => true,
            'cursor' => 'move',
            'axis' => 'y',
            'containment' => "#content",
            'start' => 'js:function(event,ui){
                        start_pos = ui.item.index(); ui.item.data("start_pos",start_pos);
                        }',
            'update' => 'js:function(event,ui){
                        start_pos = ui.item.data("start_pos") + 1;
                        end_pos = ui.item.index() + 1;
                        $.ajax({
                            type:"POST",
                            data: {"position[]":$(this).sortable("toArray")}
                        });
                     }'),
    ));
?>