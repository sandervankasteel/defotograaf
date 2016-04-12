<?php
/**
 * Roy Hendriks
 * Date: 27/11/14
 * Time: 11:49
 */
if (!isset($passError)) {
    $passError = array();
}

if (!isset($passForgot)) {
    $passForgot = array();
}
?>


<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
        <form role="form" method="POST" action="forgot">
            <div class="form-group has-feedback <?php if (isset($passError['email'])) { echo 'has-error'; } ?>">
                <label for="email">Wachtwoord vergeten?</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="<?php if (isset($passForgot['email'])) { echo $passForgot['email']; } ?>">
                <?php
                if (isset($passError['email'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $passError['email'] . '</span>';
                }
                ?>
            </div>
            <button type="submit" name="submit" class="btn btn-yellow">Send</button>
        </form>
    </div>
</div>