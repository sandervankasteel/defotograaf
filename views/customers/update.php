<?php
/**
 * Created by PhpStorm.
 * User: Tim Oosterbroek
 * Date: 27-11-2014
 * Time: 11:34
 */

/** @var /entities/Customer $customer */
/** @var /entities/Address $address */


if (isset($customer)) {

    $address = $customer->getAddress();

    $errorMessage = '<span class="alert alert-danger">&#9679; %s</span>';
    ?>
    <div class="row">
        <div
            class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
            <?php
            if (isset($createResult)) {
                if ($createResult === true) {
                    echo '<div class="alert alert-success">De gegevens zijn gewijzigd</div>';

                } else {
                    echo '<div class="alert alert-danger">Kon door een server fout de gegevens niet wijzigen</div>';
                }
            }
            ?>

            <form role="form" method="POST" action="<?php echo $customer->getId(); ?>">
                <div class="form-group has-feedback <?php if (isset($createError['firstname'])) {
                    echo 'has-error';
                } ?>">
                    <label for="firstname">Voornaam<span class="mandatory">*</span></label>
                    <input type="text" class="form-control" name="firstname" id="firstname"
                           value="<?php echo $customer->getFirstName(); ?>">
                    <?php
                    if (isset($createError['firstname'])) {
                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                        echo '<span id="helpBlock" class="help-block">' . $createError['firstname'] . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group has-feedback <?php if (isset($createError['lastname'])) {
                    echo 'has-error';
                } ?>">
                    <label for="lastname">Achternaam<span class="mandatory">*</span></label>
                    <input type="text" class="form-control" name="lastname" id="lastname"
                           value="<?php echo $customer->getLastName(); ?>">
                    <?php
                    if (isset($createError['lastname'])) {
                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                        echo '<span id="helpBlock" class="help-block">' . $createError['lastname'] . '</span>';
                    }
                    ?>
                </div>
                <div
                    class="form-group has-feedback <?php if (isset($createError['street']) || isset($createError['house_number'])) {
                        echo 'has-error';
                    } ?>">
                    <label for="street">Staatnaam & </label>
                    <label for="house_number">Huisnummer<span class="mandatory">*</span></label>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="street" id="street"
                                   value="<?php echo $address->getStreet(); ?>">

                            <div class="visible-sm visible-xs">&nbsp;</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input type="number" class="form-control" name="house_number" id="house_number"
                                   value="<?php echo $address->getHouseNumber(); ?>">

                            <div class="visible-sm visible-xs">&nbsp;</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="house_number_ad" id="house_number_ad"
                                   placeholder="toevoeging" value="<?php echo $address->getHouseNumberAd(); ?>">
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
                <div
                    class="form-group has-feedback <?php if (isset($createError['zipcode']) || isset($createError['city'])) {
                        echo 'has-error';
                    } ?>">
                    <label for="zipcode">Postcode </label>
                    <label for="city">en woonplaats<span class="mandatory">*</span></label>

                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                            <input type="text" maxlength="6" class="form-control" name="zipcode" id="zipcode"
                                   value="<?php echo $address->getZipCode(); ?>">
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                            <input type="text" class="form-control" name="city" id="city"
                                   value="<?php echo $address->getCity(); ?>">
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
                <div class="form-group has-feedback <?php if (isset($createError['phonenumber'])) {
                    echo 'has-error';
                } ?>">
                    <label for="phonenumber">Telefoon nummer<span class="mandatory">*</span></label>
                    <input type="text" class="form-control" name="phonenumber" id="phonenumber"
                           value="<?php echo $customer->getPhoneNumber(); ?>">
                    <?php
                    if (isset($createError['phonenumber'])) {
                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                        echo '<span id="helpBlock" class="help-block">' . $createError['phonenumber'] . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group has-feedback <?php if (isset($createError['email'])) {
                    echo 'has-error';
                } ?>">
                    <label for="email">E-mail<span class="mandatory">*</span></label>
                    <input type="email" class="form-control" name="email" id="email"
                           value="<?php echo $customer->getEmail(); ?>">
                    <?php
                    if (isset($createError['email'])) {
                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                        echo '<span id="helpBlock" class="help-block">' . $createError['email'] . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group has-feedback <?php if (isset($createError['password'])) {
                    echo 'has-error';
                } ?>">
                    <label for="password">Nieuwe wachtwoord</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <?php
                    if (isset($createError['password'])) {
                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                        echo '<span id="helpBlock" class="help-block">' . $createError['password'] . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group has-feedback <?php if (isset($createError['repeat_password'])) {
                    echo 'has-error';
                } ?>">
                    <label for="repeat_password">Nieuwe wachtwoord herhaling</label>
                    <input type="password" class="form-control" name="repeat_password" id="repeat_password">
                    <?php
                    if (isset($createError['repeat_password'])) {
                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                        echo '<span id="helpBlock" class="help-block">' . $createError['repeat_password'] . '</span>';
                    }
                    ?>
                </div>
                <div class="checkbox">
                    <label>
                        <input id="newsletter" type="checkbox" name="newsletter" value="newsletter"> Ja, ik wil de
                        nieuwsbrief ontvangen
                    </label>
                </div>
                <input class="btn btn-yellow" type="submit" name="update" value="Wijzig">
            </form>
            <div class="mandatory-note">
                Een veld gemarkeerd met (<span class="mandatory">*</span>) is verplicht.
            </div>
        </div>
    </div>
<?php
    if ($view_user->getRank() !== 'admin') {
        ?>
        <a href="/profile"><input class="small-button" type="submit" value="Terug naar profiel"/></a>
<?php
    }
} else {
    print("De gebruiker bestaat niet of je hebt geen toegang.");
}
?>