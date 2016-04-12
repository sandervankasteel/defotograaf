<?php
/**
 * Roy Hendriks
 * Date: 27/11/14
 * Time: 11:49
 */
if (!isset($resetError)) {
    $resetError = array();
}

if (!isset($resetValue)) {
  $resetValue = array();
}
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($editResult)) {
            if ($editResult === false) {
                echo '<div class="alert alert-danger">U wachtwoord kon niet bijgwerkt worden probeer het later nog eens</div>';
            }
            else {
                echo '<div class="alert alert-success">U wachtwoord is bijgewerkt</div>';
            }
        }
        ?>
    </div>


<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
        <form role="form" method="POST" action="vergeten">
            <div class="form-group has-feedback <?php if (isset($resetError['resetcode'])) { echo 'has-error'; } ?>">
                <label for="resetcode">Code</label>
                <input type="text" class="form-control" name="resetcode" id="resetcode" placeholder="Enter Code" value="<?php if (isset($resetValue['resetcode'])) { echo $resetValue['resetcode']; } ?>">
                <?php
                if (isset($resetError['resetcode'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $resetError['resetcode'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($resetError['password'])) { echo 'has-error'; } ?>">
                <label for="password">Wachtwoord</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Nieuw wachtwoord" value="<?php if (isset($resetValue['password'])) { echo $resetValue['password']; } ?>">
                <?php
                if (isset($resetError['password'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $resetError['password'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($resetError['repeat'])) { echo 'has-error'; } ?>">
                <label for="repeat">Bevestig wachtwoord</label>
                <input type="password" class="form-control" name="repeat" id="repeat" placeholder="Herhaal wachtwoord" value="<?php if (isset($resetValue['repeat'])) { echo $resetValue['repeat']; } ?>">
                <?php
                if (isset($resetError['repeat'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $resetError['repeat'] . '</span>';
                }
                ?>
            </div>
            <button type="submit" name="submit" class="btn btn-yellow">Bevestig</button>
        </form>
    </div>
</div>