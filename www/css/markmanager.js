function open_mark (subject, markId) {
    $('#subj_'+subject+'_'+markId).toggle();
}
function delete_mark(id){
    if (confirm('Sigur \u0219tergeți nota?')) {
        jQuery.ajax({
            'type':'POST',
            'success':function() {
                    $('#schoolmark_'+id).slideUp('fast');
                },
            'url':'index.php?r=mark/delete&id='+id+'&ajax=true',
            'cache':false,
            'data':jQuery(this).parents("form").serialize()
        });
        return false;
    } else
        return false;
}
function open_absence (subject, markId) {
    $('#abse_'+subject+'_'+markId).toggle();
}
function delete_absence(id){
    if (confirm('Sigur \u0219tergeți absența?')) {
        jQuery.ajax({
            'type':'POST',
            'success':function() {
                    $('#schoolabsence_'+id).slideUp('fast');
                },
            'url':'index.php?r=absences/delete&id='+id+'&ajax=true',
            'cache':false,
            'data':jQuery(this).parents("form").serialize()
        });
        return false;
    } else
        return false;
}
function authorize_absence(id,auth){
    jQuery.ajax({
        'type':'POST',
        'success':function() {
                $('#schoolabsence_'+id+' span.absence').toggleClass('unauthorized authorized');
                $('#schoolabsence_'+id+'_auth'+(3-auth)).css('display','inline-block');
                $('#schoolabsence_'+id+'_auth'+(auth)).css('display','none');
            },
        'url':'index.php?r=absences/authorize&id='+id+'&ajax=true',
        'cache':false,
        'data':'authorized='+auth
    });
    return false;
}
function updateAbsences (data) {
    //alert(data);
    for (i in data) {
        //alert('i='+i);
        if (i!='added') {
            for (j in data[i]) {
                //alert('j='+j);
                $('#absences_'+i).append('<div class="schoolmark" id="schoolabsence_'+j+'">\n\
                <span class="unauthorized absence">'+data[i][j]+'</span>\n\
                <span class="menu">\n\
                <a href="#" onclick="javascript:return authorize_absence('+j+',1);"\n\
                style="display:inline-block;" id="schoolabsence_'+j+'_auth">motivează</a>\n\
                <a href="#" onclick="javascript:return authorize_absence('+j+',2);"\n\
                style="display:none;" id="schoolabsence_'+j+'_auth">anulează motivarea</a>\n\
                <a href="#" onclick="javascript:return delete_absence('+j+');">șterge</a>\n\
                </span>\n\
                </div>');
            }
        }
    }
}
