<?php
/**
 * Remco Schipper
 * Date: 18/11/14
 * Time: 11:13
 */

?>

<div class="row">
    <div class="col-md-3">
        <div id="busyUploading">
            <i class="fa fa-spinner fa-spin"></i> Bezig met uploaden
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
        <ul id="photoPreview">

        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <input class="form-control" id="fileupload" type="file" name="files[]" data-url="/File_uploads/" multiple>
    </div>
    <div class="col-md-3 col-md-push-6">
        <div class="btn btn-yellow"><a href="/PhotoEdit/">Klaar met uploaden</a></div>
    </div>
</div>