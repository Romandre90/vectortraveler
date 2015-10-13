<?php
/* @var $this StepController */
/* @var $model Step */
/* @var $form CActiveForm */
?>

<?php
			
			
			
			
	switch ($element->typeId) {		
			case 13:
			$id = $element->id;
                //echo "<table class='table'><tr><td>".CHtml::link("Excel output", array('element/excelFile', 'id'=>$id, 'issueId'=> $issueId));//el bueno
				
				$url="/index.php/element/excelFile/".$id."?"."issueId=".$issueId;
				echo "<table class='table'><tr><td><input class='Excel' id='hola' type='button' value='Excel' onclick=location.href='".$url."';>";
				//<div style='text-align:right'><br><a id=traceability style='color:black; text-align:right !important; background-color:rgb(153,204,0); border-radius:4px; ' onclick=toggle_visibility(&#39;excel&#39;)>&nbsp;Import excel file&nbsp;</a></td><br></div>";//
                $i = 0;
                
				//echo "<a id=traceability style='color:black; background-color:rgb(200,200,200); border-radius:4px; ' onclick=toggle_visibility(&#39;info&#39;)>&nbsp;Click for Info&nbsp;</a></td>";
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
							
							echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'>
								<input style='margin:2px;' name='Table[$id][$r][$index]' type='text' value ='$res'><br>
								<div class='info' style='display: none; font-size:small; padding:3px; background-color:rgb(220,220,220); border-radius:4px'>" . $element->getUserDateForTables($issueId, $r, $index)."</div></td>";
						} else echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'>
								<input style='margin:2px;' name='Table[$id][$r][$index]' type='text' value =' '><br>
								<div class='info' style='display: none; font-size:small; padding:3px; background-color:rgb(220,220,220); border-radius:4px'>" . $element->getUserDateForTables($issueId, $r, $index)."</div></td>";
					}
                    echo "</tr>";
                    $r++;
                }
                echo "</table>";
				//$element->excelFile($element, $issueId);
				
				
				break;
            case 14:
				
				$info=false;
                $i=0;
                $id = $element->id;
				
				
				$url="/index.php/element/excelFileOutput14/".$id."?"."issueId=".$issueId;
				echo "<table class='table'><tr><td><input class='Excel' id='hola' type='button' value='Excel' onclick=location.href='".$url."';>";
				
				
				
				
                //echo "<table class='table'><tr><td>".CHtml::link("Excel output", array('element/excelFileOutput14', 'id'=>$id, 'issueId'=> $issueId));
				//echo "<a id=traceability style='color:black; background-color:rgb(200,200,200); border-radius:4px; ' on=toggle_visibility(&#39;traceability$id&#39;)> &nbsp;Click for Info&nbsp; </a></td>";
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
						if ($res=="checked") echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'>
						<input  name='Table[$id][$r][$index]' $res type='radio' value='on'>Yes<input name='Table[$id][$r][$index]'  type='radio' value='off'>No
						<div class='info' style='font-size:small;'><br>
						<p class='info' style='display:none; padding:4px; border-radius:3px; background-color:rgb(220,220,220)'>
						" . $element->getUserDateForTables($issueId, $r, $index)."</p>
						</div>
						</td>";
						 
						else if($res=="off") echo "<td title='".$element->getUserDateForTables2($issueId, $r, $index)."'>
						<input name='Table[$id][$r][$index]' type='radio' value='on'>Yes<input name='Table[$id][$r][$index]' checked  type='radio' value='off'>No
						<div class='info' style='font-size:small;'><br>
						<p class='info' style='display: none; padding:4px; border-radius:3px;background-color:rgb(220,220,220);'>
						" . $element->getUserDateForTables($issueId, $r, $index)."</div>
						</td>";
						else echo "<td><input name='Table[$id][$r][$index]' type='radio' value='on'>Yes<input name='Table[$id][$r][$index]'  type='radio' value='off'>No</td>";
						//echo "<input type='radio' onclick=toggle_visibility();>tr</input><div id='traceability'><br>" . $element->getUserDateForTables($issueId, $r, $index)."</div>";
					}
                    $r++;
                    echo "</tr>";
                }
                echo "</table>";
				//echo "<br>" . $element->getUserDate($issueId);// . ' ' . $element->dateCreated;
				//echo $this->renderPartial('../traveler/_steps', array('model'=>new Result,'steps'=>$model->traveler->stepParent,'issueId'=>$model->id)) ;
                
				break;
	}
	
    ?>