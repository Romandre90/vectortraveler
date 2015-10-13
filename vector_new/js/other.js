/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function test(value){
    if(value == 4){
        $('#Nonconformity_activityOther').attr('disabled',false).focus();
    }else{
        $('#Nonconformity_activityOther').attr('disabled',true);
    }
}