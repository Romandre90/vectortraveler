type = 1;
function control(value){
    if(value > 4 && value < 8){
        $('#multi').show();
        $('#textBox1').focus().select();  
        $('#element-form :submit, #element-form :button').attr('disabled',!$("#textBox1").val()); 
    }else{
        $('#element-form :submit, #element-form :button').attr('disabled',false);
        $('#multi').hide();
        $('#upload').hide();
    }
    type= value;
}

$(document).ready(function(){

    var counter = 2;
    $('#removeButton').hide();
    
    $('#textBox1').keyup(function(){
          $('#element-form :submit, #element-form :button').attr('disabled',!$(this).val() );  
    });
    control($('#list').val());

    $('#list').change(function(){
        control(this.value);
    });
    
    $("#addButton").click(function () { 
  
	$("#TextBoxesGroup").append('<div id="TextBoxDiv'+counter+'"><input id="textBox'+counter+'" type="text" name="Element[multi][]" maxlength="50" id="textbox' + counter + '" required="true" value="Option '+counter+'" ></div>');
        $('#textBox'+counter).focus().select();
	counter++;
        $('#removeButton').show();
     });
     
     $("#removeButton").click(function () {
	if(counter<3){
            $('#removeButton').hide();
          return false;
        }   
 
	counter--;
 
        $("#TextBoxDiv" + counter).remove();
        if(counter<3){
            $('#removeButton').hide();
          return false;
        } 
 
     });
 
     $("#getButtonValue").click(function () {
 
	var msg = '';
	for(i=1; i<counter; i++){
   	  msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
	}
    	  alert(msg);
     });
  });