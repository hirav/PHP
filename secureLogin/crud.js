
$(function(){
    $("#loading").hide();
    //acknowledgement message
    var message_status = $("#status");
    $("td[contenteditable=true]").blur(function(){
        var field_userid = $(this).attr("id") ;
        var value = $(this).text() ;
       // var data = 'Value of ' + field_userid ' updated successfully';
        $.post('update.php' , field_userid + "=" + value, function(data){
            if(data != '')
            {
                var index = data.indexOf("<body>") + 6;
                var msg = data.slice(index);
                message_status.show();
                message_status.text(msg);
                //hide the message
                setTimeout(function(){message_status.hide()},1000);
            }
        });
    });
});
