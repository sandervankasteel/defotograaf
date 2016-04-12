<?php
/**
 * Remco Schipper
 * Date: 06/12/14
 * Time: 15:29
 */
?>
<form role="form" method="POST" action="/orders/create/2">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Uw gegevens</div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($createError['firstname'])) { echo 'has-error'; } ?>">
                        <label for="firstname">Voornaam<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php if (isset($createValues['firstname'])) { echo $createValues['firstname']; } ?>">
                        <?php
                        if (isset($createError['firstname'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['firstname'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['lastname'])) { echo 'has-error'; } ?>">
                        <label for="lastname">Achternaam<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php if (isset($createValues['lastname'])) { echo $createValues['lastname']; } ?>">
                        <?php
                        if (isset($createError['lastname'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['lastname'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['phonenumber']))  { echo 'has-error'; } ?>">
                        <label for="phonenumber">Telefoon nummer<span class="mandatory">*</span></label>
                        <input type="text"  class="form-control" name="phonenumber" id="phonenumber" value="<?php if (isset($createValues['phonenumber'])) { echo $createValues['phonenumber']; } ?>">
                        <?php
                        if (isset($createError['phonenumber'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['phonenumber'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['email'])) { echo 'has-error'; } ?>">
                        <label for="email">E-mail<span class="mandatory">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php if (isset($createValues['email'])) { echo $createValues['email']; } ?>">
                        <?php
                        if (isset($createError['email'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['email'] . '</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Het verzend adres</div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($createError['street']) || isset($createError['house_number'])) { echo 'has-error'; } ?>">
                        <label for="street">Staatnaam & </label>
                        <label for="house_number">Huisnummer<span class="mandatory">*</span></label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="street" id="street" value="<?php if (isset($createValues['street'])) { echo $createValues['street']; } ?>">
                                <div class="visible-sm visible-xs">&nbsp;</div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="house_number" id="house_number" value="<?php if (isset($createValues['house_number'])) { echo $createValues['house_number']; } ?>">
                                <div class="visible-sm visible-xs">&nbsp;</div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="house_number_ad" id="house_number_ad" placeholder="toevoeging" value="<?php if (isset($createValues['house_number_ad'])) { echo $createValues['house_number_ad']; } ?>">
                                <div class="visible-sm visible-xs">&nbsp;</div>
                            </div>
                        </div>
                        <?php
                        if (isset($createError['street'])) {
                            echo '<span id="helpBlock" class="help-block">' . $createError['street'] . '</span>';
                        }
                        if (isset($createError['house_number'])) {
                            echo '<span id="helpBlock" class="help-block">' . $createError['house_number'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['zipcode']) || isset($createError['city'])) { echo 'has-error'; } ?>">
                        <label for="zipcode">Postcode </label>
                        <label for="city">en woonplaats<span class="mandatory">*</span></label>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                                <input type="text" maxlength="6" class="form-control" name="zipcode" id="zipcode" value="<?php if (isset($createValues['zipcode'])) { echo $createValues['zipcode']; } ?>">
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" name="city" id="city" value="<?php if (isset($createValues['city'])) { echo $createValues['city']; } ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($createError['zipcode'])) {
                            echo '<span id="helpBlock" class="help-block">' . $createError['zipcode'] . '</span>';
                        }
                        if (isset($createError['city'])) {
                            echo '<span id="helpBlock" class="help-block">' . $createError['city'] . '</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="mandatory-note">
            Een veld gemarkeerd met (<span class="mandatory">*</span>) is verplicht.
        </div>
        <input class="small-button pull-right" type="submit" name="submit" value="Ga door">
    </div>
</form>