<?php
/**
 * Remco Schipper en Roy Hendriks
 * Date: 24/11/14
 * Time: 13:27
 */
if (!isset($loginError)) {
    $loginError = array();
}

if (!isset($loginValues)) {
    $loginValues = array();
}
?>

<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
        <?php
        if(isset($loginError['account'])) {
            ?>
            <div class="alert alert-danger"><?php echo $loginError['account']; ?></div>
            <?php
        }
        ?>
        <form role="form" method="POST" action="inloggen">
            <div class="form-group has-feedback <?php if (isset($loginError['email'])) { echo 'has-error'; } ?>">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="<?php if (isset($loginValues['email'])) { echo $loginValues['email']; } ?>">
                <?php
                if (isset($loginError['email'])) {
                    ?>
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <?php
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($loginError['password'])) { echo 'has-error'; } ?>">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <?php
                if (isset($loginError['password'])) {
                    ?>
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <?php
                }
                ?>
            </div>
            <button type="submit" name="login" class="btn btn-yellow">Login</button>
        </form>
        <a href="vergeten">Wachtwoord vergeten</a>
    </div>
</div>