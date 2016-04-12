<!--Joey Smit-->
<?php
/** @var \entities\Order $order */

if (isset($order)) {
    $lines = $order->getLines();
    ?>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover({'trigger': 'hover', 'placement': 'right', html: true});
        })
    </script>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title visible-xs-inline-block visible-lg-inline-block visible-sm-inline-block visible-md-inline-block pull-left">
                        Order nummer: <strong><?php print($order->getId()); ?></strong>
                    </h3>

                    <h3 class="panel-title visible-xs-inline-block visible-lg-inline-block visible-sm-inline-block visible-md-inline-block pull-right">
                        <?php print($order->getOrderPlaced()->format('d-m-Y')); ?>
                    </h3>

                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Klantnummer:</th>
                                <td><?php print($order->getUser()->getId()); ?></td>
                            </tr>
                            <tr>
                                <th>Naam:</th>
                                <td><?php print($order->getUser()->getFirstName() . " " . $order->getUser()->getLastName()); ?></td>
                            </tr>
                            <tr>
                                <th>Emailadres:</th>
                                <td><?php print($order->getUser()->getEmail()); ?></td>
                            </tr>
                            <tr>
                                <th>Telefoonnummer:</th>
                                <td><?php print($order->getUser()->getPhoneNumber()); ?></td>
                            </tr>
                            <tr>
                                <th>Adres:</th>
                                <td>
                                    <?php print($order->getShippingAddress()->getStreet() . " " . $order->getShippingAddress()->getHouseNumber() . $order->getShippingAddress()->getHouseNumberAd()); ?>
                                    <br/>
                                    <?php print($order->getShippingAddress()->getZipCode() . " " . $order->getShippingAddress()->getCity()); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <?php
                        if ($view_user->getRank() === 'admin') {
                            ?>
                            <a href="/customers/update/<?php print($order->getUser()->getId()); ?>">Klantgegevens
                                bewerken</a>
                        <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Betaal & verzend methode</h3></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Betaal methode:</th>
                                <td><?php print($order->getPaymentMethod()->getName()); ?></td>
                            </tr>
                            <tr>
                                <th>Verzend methode:</th>
                                <td><?php print($order->getShippingMethod()->getName()); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel-footer"></div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <?php
            if ($view_user->getRank() === 'admin') {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Status</h3></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Order status:</th>
                                <td>
                                    <form action="<?php print($order->getId()); ?>" method="POST">
                                        <div class="form-group">
                                            <select name="statusUpdate" class="form-control" id="selectStatus">
                                                <option><?php print($order->getOrderStatus()->getDescription()); ?></option>
                                                <br/>
                                                <option disabled>Selecteer een status</option>
                                                <?php
                                                //Alle statussen printen zodat je deze uit een dropdown kunt selecteren
                                                foreach ($statuses as $status) {
                                                    if($order->getOrderStatus()->getId() !== $status->getId()) {
                                                        echo '<option value="' . $status->getId() . '">' . $status->getDescription() . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <input class="btn btn-yellow pull-right" onclick="reloadPage()" type="submit"
                                               name="submit" value="Wijzig"/>
                                    </form>
                                    <?php
                                    // Alls alles goed is gegaan wordt er een succes bericht weergegeven en anders fout melding.
                                    if (isset($viewResult)) {
                                        if ($viewResult === true) {
                                            print("<div class=\"alert alert-success text-center col-md-8 col-sm-7 col-lg-7 col-md-7 col-xs-7\">Status aangepast.</div>");
                                        } else {
                                            print("<div class=\"alert alert-danger text-center col-md-8 col-sm-7 col-lg-7 col-md-7 col-xs-7\">Kies een status.</div>");
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel-footer"><strong>Let op!</strong> De klant krijgt een email als de status aangepast
                    wordt.
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Bestelling</h3></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered orderView">
                    <thead>
                    <tr>
                        <th>Foto ID</th>
                        <th>Product ID</th>
                        <th>Artikel</th>
                        <th>Effect</th>
                        <th>Fill-in/fit-in</th>
                        <th>Stukprijs</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                    </tr>
                    </thead>
                    <?php
                    /** @var \entities\OrderProduct $line */
                    //Subtotaal op nul zodat je een begin variabele hebt
                    $subtotal = 0;
                    foreach ($lines as $line) {
                        $stukprijs = round($line->getPrice() / $line->getAmount(2.), 2)
                        ?>
                        <tr>
                            <td><span style="display: block;" data-toggle="popover"
                                      title="Foto ID: <?php print($line->getPhotoId()); ?>"
                                      data-content="<img width='200px' src='<?php print("http://" . $_SERVER['SERVER_NAME'] . "/" . $line->getPhoto()->getFileName()); ?>' />"><?php print($line->getPhotoId()); ?></span>
                            </td>
                            <td><?php print($line->getProduct()->getId()); ?></td>
                            <td><?php print($line->getProduct()->getName()); ?></td>
                            <td><?php if ($line->getPhoto()->getPhotoEffect() != NULL) {
                                    print($line->getPhoto()->getPhotoEffect()->getName());
                                } else {
                                    print("Geen"); //Er is geen foto effect (dus gewoon kleur)
                                }; ?></td>
                            <td><?php if ($line->getPhoto()->getPhotoFillFit() != NULL) {
                                    print($line->getPhoto()->getPhotoFillFit()->getName());
                                } else {
                                    print("N.V.T."); //In princiepe is het OF fill-in OF fit-in maar voor de zekerheid wordt deze opgevangen als het leeg is
                                }; ?></td>
                            <td>&euro;<?php print(number_format($stukprijs, 2)); ?></td>
                            <td><?php print($line->getAmount()); ?></td>
                            <td>&euro;<?php print(number_format(round($line->getPrice(), 2), 2)); ?></td>
                        </tr>
                        <?php
                        //Subtotaal optellen
                        $subtotal += $line->getPrice();

                        $shipment_max_number_of_items = array($line->getProduct()->getMaxBeforeShippingCosts());

                        $min_shipment_cost = $line->getProduct()->getShippingCosts();

                        $max_shipment_cost = $line->getProduct()->getHigherShippingCosts();







                        var_dump($shipment_max_number_of_items);

                    }
                    //Alles voor de kortingscode in een variabel zetten
                    $discount_code = $order->getDiscountCode();

                    if($discount_code != NULL) {
                        $used_discount_code = $order->getDiscountCode()->getCode();
                        $discount_percentage = $order->getDiscountCode()->getPercentage();
                        $discount_price = $order->getDiscountCode()->getFixedAmount();
                        $discount_code_description = $order->getDiscountCode()->getDescription();
                        $discount_code_id = $order->getDiscountCode()->getDiscountId();
                    }

                    $new_subtotal = 0;

                    //Als de discountcode niet op nul staat wordt het onderstaande uitgevoerd, anders is het nieuwe subtotaal hetzelfde als het oude subtotaal.
                    //Als percentage null is in de kortingscode tabel wordt het subtotaal uitgerekent met een vast bedrag, anders wordt het met percentage uitgerekend.
                    if ($discount_code != NULL) {
                        if ($discount_percentage == NULL) {
                            $new_subtotal = $subtotal - $discount_price;
                        } elseif ($discount_price == NULL) {
                            $new_subtotal = $subtotal - ($subtotal / 100 * $discount_percentage);
                        }
                    } else {
                        $new_subtotal = $subtotal;
                    }

                    $startup_costs = $order->getStartingCost()->getPrice();
                    $shipment_method = $order->getShippingMethod()->getName();
                    $shipment_method_price = $order->getShippingMethod()->getPrice();

                    //Bedrag voor BTW uitrekenen
                    $subsubtotal = $new_subtotal + $startup_costs + $shipment_method_price;

                    //Tijdelijke fix
                    $btwfix = (100 + $btwPercentage->getValue()) / 100;

                    //Totaal bedrag zonder btw
                    $btwprice = round($subsubtotal / $btwfix, 2);

                    //BTW bedrag uitrekenen
                    $btwprice = $subsubtotal - $btwprice;

                    //Subtotaal uitrekenen
                    $total = round($subsubtotal, 2);

                    ?>
                    <tr>
                        <td class="text-right" colspan="7"><strong>Subtotaal: </strong></td>
                        <td>&euro;<?php print(number_format($subtotal, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="7"><strong>
                                <?php
                                //Kijken of er een discount code is. Zo nee dan laat hij tekst zien.
                                if ($discount_code == NULL) {
                                    print("Geen kortingscode");
                                } else {
                                    print("Korting: <span class='font-weight-normal'>(" . $used_discount_code . ")<br /><span class='text-info'>" . $discount_code_description . "</span></span>");
                                }
                                ?>
                            </strong>
                        </td>
                        <td>
                            <?php
                            //Kortingscode bedrag laten zien (of percentage)
                            if ($discount_code != NULL) {
                                if ($discount_percentage == NULL) {
                                    print ("&euro;" . $discount_price);
                                } elseif ($discount_price == NULL) {
                                    print($discount_percentage . "%");
                                }
                            } else {
                                print("");
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="7"><strong>Opstartkosten: </strong></td>
                        <td>&euro;<?php print(number_format($startup_costs, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="7"><strong>Verzendkosten: <br/>
                                (<?php print($shipment_method); ?>)</strong></td>
                        <td>&euro;<?php print(number_format($shipment_method_price, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="7"><strong>BTW (<?php echo $btwPercentage->getValue(); ?>
                                %): </strong></td>
                        <td>&euro;<?php print(number_format($btwprice, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="7"><strong>Totaal: </strong></td>
                        <td>&euro;<?php print(number_format($total, 2)); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="panel-footer"></div>
    </div>
    <?php
    //Als je admin bent dan laat hij de terug knop zien, als je dat niet bent dan is er een knop om terug te gaan naar je profiel
    if ($view_user->getRank() === 'admin') {
        ?>
        <a href="/orders/"><input class="btn btn-yellow" type="submit" value="Terug"/></a>
    <?php
    } else {
        ?>
        <a href="/profile"><input class="btn btn-yellow" type="submit" value="Terug naar profiel"/></a>
        <?php
    }
}
else {
    print("De bestelling bestaat niet of je hebt geen toegang.");
}
?>