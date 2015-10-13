<?php

echo "<div id=ajaxImage style=display:inline class='image[$id]'>".CHtml::image(Yii::app()->request->baseUrl . '/files/' . $id,'', array('id'=>'ajaxImage', 'style'=>'display:block;'));
echo "</div></br>";
//echo "<input type=button class='image[$id]'  onClick=javascript:hideImage('image[$id]'); value=Hide>"; //NO ES NECESARIO; SE PUEDE HACER CON EL QUE LA ENSEÑA
?>