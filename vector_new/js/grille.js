var column = 1;
var row = 1;

$(document).ready(function(){

    majR();
    $("#addRow").click(function () { 
        row++;
	$("#rows").append('<div id="row'+row+'"><input id="r'+row+'" type="text" required name="Rows[]" maxlength="50"  value="Label Row '+row+'" ><span class="dr" id="delR" onclick="delR(\'#row'+row+'\')"> x </span></div>');
        $('#r'+row).focus().select();
         majR();
     });
     
     $("#addColumn").click(function () { 
        column++;
	$("#columns").append('<div id="column'+column+'"><input id="c'+column+'" required type="text" name="Columns[]" maxlength="50" value="Label Column '+column+'" ><span class="dc" id="delC" onclick="delC(\'#column'+column+'\')"> x </span></div>');
        $('#c'+column).focus().select();
         majR();
     });
    
  });
  
  function majR(){
      if(column>1){
          $('.dc').show();
      }else{
          $('.dc').hide();
      }
      if(row>1){
          $('.dr').show();
      }else{
          $('.dr').hide();
      }
      if(column>4){
          $('#addColumn').hide();
      }else{
          $('#addColumn').show();
      }
  }
  function delC(elem){
      $(elem).remove();
      column--;
       majR();
  }
  
    function delR(elem){
      $(elem).remove();
      row--;
      majR();
  }