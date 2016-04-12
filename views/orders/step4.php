<?php
/**
 * Remco Schipper
 * Date: 16/12/14
 * Time: 16:14
 */

/** @var \entities\Order $viewOrder */
/** @var \entities\OrderProduct[] $viewLines */
/** @var \entities\Configuration[] $btwPercentage */
/** @var \entities\PaymentMethod[] $paymentMethods */
/** @var \entities\ShippingMethod[] $shippingMethods */
/** @var \services\Text $view_text */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($couponResult)) {
            if ($couponResult === false) {
                echo $view_text->get('coupons.use.error');
            }
            else {
                echo $view_text->get('coupons.use.success');
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Klantgegevens
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <td>Klantnummer:</td>
                        <td><?php print($viewOrder->getUser()->getId()); ?></td>
                    </tr>
                    <tr>
                        <td>Naam:</td>
                        <td><?php print($viewOrder->getUser()->getFirstName() . " " . $viewOrder->getUser()->getLastName()); ?></td>
                    </tr>
                    <tr>
                        <td>Emailadres:</td>
                        <td><?php print($viewOrder->getUser()->getEmail()); ?></td>
                    </tr>
                    <tr>
                        <td>Telefoonnummer:</td>
                        <td><?php print($viewOrder->getUser()->getPhoneNumber()); ?></td>
                    </tr>
                    <tr>
                        <td>Adres:</td>
                        <td>
                            <?php print($viewOrder->getShippingAddress()->getStreet() . " " . $viewOrder->getShippingAddress()->getHouseNumber() . $viewOrder->getShippingAddress()->getHouseNumberAd()); ?>
                            <br/>
                            <?php print($viewOrder->getShippingAddress()->getZipCode() . " " . $viewOrder->getShippingAddress()->getCity()); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Betaal & verzend methode</h3></div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <td>Betaal methode:</td>
                                <td>
                                    <select class="form-control" id="paymentmethod" name="paymentmethod">
                                        <?php
                                        foreach($paymentMethods as $paymentMethod) {
                                            echo '<option value="' . $paymentMethod->getId() . '">' . $paymentMethod->getDescription() . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Verzend methode:</td>
                                <td>
                                    <select class="form-control" id="shippingmethod" name="shippingmethod">
                                        <?php
                                        foreach($shippingMethods as $shippingMethod) {
                                            echo '<option value="' . $shippingMethod->getId() . '">' . $shippingMethod->getName() . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Kortingscode</h3></div>
                    <div class="panel-body" style="padding: 10px">
                        <form method="POST" action="/bestelling/plaatsen/4">
                            <div class="input-group">
                                <input type="text" name="coupon" class="form-control">
                                <span class="input-group-btn">
                                    <?php
                                    $text = 'toevoegen';

                                    if ($viewOrder->getDiscountCode() !== null) {
                                        $text = 'wisselen';
                                    }
                                    ?>
                                    <input class="btn btn-yellow" type="submit" value="<?php echo $text; ?>" name="sbmCoupon"/>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Bestelling</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Foto</th>
                        <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Artikel</th>
                        <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Effect</th>
                        <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1">Stukprijs</th>
                        <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1">Aantal</th>
                        <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1">Prijs</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var \entities\OrderProduct $line */
                    $subtotal = 0;

                    foreach ($viewLines as $line) {
                        $photo = $line->getPhoto();
                        $product = $line->getProduct();
                        $effect = $photo->getPhotoEffect();
                        $fillFit = $photo->getPhotoFillFit();

                        $completeText = $fillFit->getName();

                        if ($effect !== null) {
                            $completeText .= ' (' . $effect->getName() . ')';
                        }

                        $ppp = round($line->getPrice() / $line->getAmount(), 2);
                        ?>
                        <tr>
                            <td>img1212112</td>
                            <td><?php echo $product->getName(); ?></td>
                            <td><?php echo $completeText; ?></td>
                            <td>&euro;<?php print(number_format($ppp, 2)); ?></td>
                            <td><?php print($line->getAmount()); ?></td>
                            <td>&euro;<?php print(number_format($line->getPrice(), 2)); ?></td>
                        </tr>
                        <?php
                        //Subtotaal optellen
                        $subtotal += $line->getPrice();
                    }
                    ?>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                    <?php
                    //Alles voor de kortingscode in een variabel zetten
                    $discount_code = $viewOrder->getDiscountCode();

                    $new_subtotal = 0;
                    if($discount_code != NULL) {
                        $used_discount_code = $viewOrder->getDiscountCode()->getCode();
                        $discount_percentage = $viewOrder->getDiscountCode()->getPercentage();
                        $discount_price = $viewOrder->getDiscountCode()->getFixedAmount();
                        $discount_code_description = $viewOrder->getDiscountCode()->getDescription();
                        $discount_code_id = $viewOrder->getDiscountCode()->getDiscountId();

                        if ($discount_percentage == NULL) {
                            $new_subtotal = $subtotal - $discount_price;
                        }
                        elseif ($discount_price == NULL) {
                            $new_subtotal = $subtotal - ($subtotal / 100 * $discount_percentage);
                        }
                        else {
                            $new_subtotal = $subtotal;
                        }
                    }
                    else {
                        $new_subtotal = $subtotal;
                    }

                    $startup_costs = $viewOrder->getStartingCost()->getPrice();

                    //Bedrag voor BTW uitrekenen
                    $subsubtotal = $new_subtotal + $startup_costs;

                    //BTW percentage uitrekenen dmv gegeven uit DB
                    $btwprice = round($subsubtotal / 100 * $btwPercentage->getValue(), 2);

                    //Subtotaal uitrekenen
                    $total = round($subsubtotal + $btwprice, 2);

                    ?>
                    <tr>
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11"><div class="text-right"><strong>Subtotaal: </strong></div></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&euro;<?php print(number_format($subtotal, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11"><div class="text-right"><strong>Kortingscode: </strong></div></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                            <?php
                            if (isset($discount_price)) {
                                print ("&euro;" . $discount_price);
                            } elseif (isset($discount_percentage)) {
                                print($discount_percentage . "%");
                            }
                            else {
                                echo '&euro;0,00';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11"><div class="text-right"><strong>Opstartkosten: </strong></div></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&euro;<?php print(number_format($startup_costs, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                            <div class="text-right"><strong>BTW (<?php echo $btwPercentage->getValue(); ?>%): </strong></div>
                        </td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&euro;<?php print(number_format($btwprice, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11"><div class="text-right"><strong>Totaal: </strong></div></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&euro;<?php print(number_format($total, 2)); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-yellow pull-right" id="goToNextStep">Volgende stap</button>
    </div>
</div>