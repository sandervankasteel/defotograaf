/**
 * Created by sander on 21-11-14.
 */
$(document).ready(function(){

    $('#busyUploading').hide();

    $(function() {
        $('#fileupload').fileupload({
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result, function (index, file) {
                    $('<li><img src="/' + file + '" height="75" width="136"></li>').appendTo('#photoPreview');
                });
            }
        }).bind('fileuploadstart', function(){
            $('#busyUploading').show();
            $('#fileupload').hide();
        }).bind('fileuploaddone', function() {
            $('#busyUploading').hide();
            $('#fileupload').show();
        });
    });
});