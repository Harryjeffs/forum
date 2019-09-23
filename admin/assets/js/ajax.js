/**
 * Created by JEFFH14 on 17/07/2017.
 */
$(document).ready(function(){
    $("#admin-edit-form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type:"POST",
            url:"/forum/admin/functions/inc/edit.php",
            data: $("#admin-edit-form").serialize()+"&user_id="+user_id,
            dataType:"json",
            success: function(data){
                $.each(data, function(idx, obj) {
                    new PNotify({
                        title: obj.title,
                        text: obj.text,
                        type: obj.type
                    });
                });
                $('input:checkbox').removeAttr('checked');
            }
        });
    });
});