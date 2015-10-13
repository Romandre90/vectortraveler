projects = new Array();

function onChangeFilter(o){
    $.ajax({
        url: "preferences",
        type: "POST",
        data: "project="+o.id,
    });
    o.checked ? $('.'+o.id).show('slow') : $('.'+o.id).hide('slow');
}

function onChangeFilter2(id,hide){
	$.ajax({
        url: "preferences",
        type: "POST",
        data: {project:id, hide:hide},
    });
	if(!hide)
		$('.'+id ).show('slow');
	else
		$('.'+id ).hide('slow');

}

function addProject(p){
	projects[projects.length] = "p"+p;
}

function update(){
	for(i = 0 ; i < projects.length ; i++){
		if(document.getElementById(projects[i]).checked)
			onChangeFilter2(projects[i],0);
		else
			onChangeFilter2(projects[i],1);
	}
}


$(function(){
	$(window).load(function(){
		if(projects.length == $(".project:checked").length){
			$("#selectAll").attr("checked","checked");
		} else{
			$("#selectAll").removeAttr("checked");
		}
	});
	//add multiple selecti / deselect functionality
    $("#selectAll").click(function(){
      $(".project").prop('checked', this.checked);
	  update();
    });
	//if all checkbox are selected, check the selectall checkbox and viceversa
	$(".project").click(function(){
		if(projects.length == $(".project:checked").length){
			$("#selectAll").attr("checked","checked");
		} else{
			$("#selectAll").removeAttr("checked");
			if(document.getElementById($(this).attr("id")).checked)
				onChangeFilter2($(this).attr("id"),0);
			else
				onChangeFilter2($(this).attr("id"),1);
			}
	});
});