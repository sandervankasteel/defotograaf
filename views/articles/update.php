<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 7-12-2014
 * Time: 11:33
 */
/** @var \entities\Product[] $products */
if (!isset($createError)) {
    $createError = array();
}
if (!isset($createValues)) {
    $createValues = array();
}

$errorMessage = '<span class="alert alert-danger">&#9679; %s</span>';
?>

<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
        <?php
        if (isset($createResult)) {
            if ($createResult === true) {
                echo '<div class="alert alert-success">Uw artikel is aangemaakt</div>';

            } else {
                echo '<div class="alert alert-danger">Kon door een server fout het artikel niet maken</div>';
            }
        }

        ?>
        <form role="form" method="POST" action="<?php echo $products->getId(); ?>">
            <div class="form-group has-feedback <?php if (isset($createError['article_name'])) {
                echo 'has-error';
            } ?>">
                <label for="article_name">Naam<span class="mandatory">*</span></label>
                <input type="text" class="form-control" name="article_name" id="article_name"
                       value="<?php echo $products->getName(); ?>">
                <?php
                if (isset($createError['article_name'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['article_name'] . '</span>';
                }
                ?>
            </div>

            <div class="form-group has-feedback <?php if (isset($editError['dim_length'])) {
                echo 'has-error';
            } ?>">
                <label for="dim_length">Lengte<span class="mandatory">*</span></label>

                <div class="input-group">
                    <input type="text" class="form-control" name="dim_length" id="dim_length"
                           value="<?php echo $products->getDimLength(); ?>">
                    <span class="input-group-addon">cm</span>
                </div>
                <?php
                if (isset($editError['dim_length'])) {
                    echo '<span id="helpBlock" class="help-block">' . $editError['dim_length'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($editError['dim_width'])) {
                echo 'has-error';
            } ?>">
                <label for="dim_width">Breedte<span class="mandatory">*</span></label>

                <div class="input-group">
                    <input type="text" class="form-control" name="dim_width" id="dim_width"
                           value="<?php echo $products->getDimWidth(); ?>">
                    <span class="input-group-addon">cm</span>
                </div>
                <?php
                if (isset($editError['dim_width'])) {
                    echo '<span id="helpBlock" class="help-block">' . $editError['dim_width'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($createError['price'])) {
                echo 'has-error';
            } ?>">
                <label for="price">Prijs<span class="mandatory">*</span></label>
                <input type="text" class="form-control" name="price" id="price"
                       value="<?php echo $products->getPrice(); ?>">
                <?php
                if (isset($createError['price'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['price'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($createError['shippingcosts'])) {
                echo 'has-error';
            } ?>">
                <label for="shippingcosts">Verzendkosten<span class="mandatory">*</span></label>
                <input type="text" class="form-control" name="shippingcosts" id="shippingcosts"
                       value="<?php echo $products->getShippingCosts(); ?>">
                <?php
                if (isset($createError['shippingcosts'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['shippingcosts'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($createError['delivery_time'])) {
                echo 'has-error';
            } ?>">
                <label for="delivery_time">Levertijd<span class="mandatory">*</span></label>

                <div class="input-group">
                    <input type="text" class="form-control" name="delivery_time" id="delivery_time"
                           value="<?php echo $products->getDeliveryTime(); ?>">
                    <span class="input-group-addon">dag(en)</span>
                </div>
                <?php
                if (isset($createError['delivery_time'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['delivery_time'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($createError['higher_shippingcosts'])) {
                echo 'has-error';
            } ?>">
                <label for="delivery_time">Verzendkosten voor meer dan X besteld<span class="mandatory">*</span></label>

                <div class="input-group">
                    <input type="text" class="form-control" name="higher_shippingcosts" id="higher_shippingcosts"
                           value="<?php echo $products->getHigherShippingCosts(); ?>">

                </div>
                <?php
                if (isset($createError['higher_shippingcosts'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['higher_shippingcosts'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($createError['max_before_hshippingcosts'])) {
                echo 'has-error';
            } ?>">
                <label for="max_before_hshippingcosts">Maximaal aantal voor hogere verzendkosten gerekend worden<span
                        class="mandatory">*</span></label>

                <div class="input-group">
                    <input type="text" class="form-control" name="max_before_hshippingcosts"
                           id="max_before_hshippingcosts"
                           value="<?php echo $products->getHigherShippingCosts(); ?>">
                    <span class="input-group-addon">stuks</span>
                </div>
                <?php
                if (isset($createError['max_before_hshippingcosts'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['max_before_hshippingcosts'] . '</span>';
                }
                ?>
            </div>
            <div class="form-group has-feedback <?php if (isset($createError['dropdownlocation'])) {
                echo 'has-error';
            } ?>">
                <label for="dropdownlocation">Dropdown locatie</label>

                <div class="input-group">
                    <input type="text" class="form-control" name="dropdownlocation" id="dropdownlocation"
                           value="<?php echo $products->getDropdownLocation(); ?>">

                </div>
                <?php
                if (isset($createError['dropdownlocation'])) {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="helpBlock" class="help-block">' . $createError['dropdownlocation'] . '</span>';
                }
                ?>
            </div>
            <div class="radio">
                <label for="Is_active"><B>Artikel is actief</B></label><br/>
                <input id="radio1" type="radio" name="is_active" value="1"
                       <?php if (1 == $products->isActive()): ?>checked='checked'<?php endif; ?>> <label
                    for="radio1">Ja</label>
                <br/>
                <input id="radio2" type="radio" name="is_active" value="0"
                       <?php if (0 == $products->isActive()): ?>checked='checked'<?php endif; ?>> <label
                    for="radio2">nee</label>
            </div>
            <input class="btn btn-yellow" type="submit" name="update" value="Wijzig">
            <button class="btn btn-yellow"><a href="/articles/index/">Artikel overzicht</a></button>
        </form>
        <div class="mandatory-note">
            Een veld gemarkeerd met (<span class="mandatory">*</span>) is verplicht.
        </div>
    </div>
</div>