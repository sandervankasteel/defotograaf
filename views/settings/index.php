<?php
/**
 * Remco Schipper
 * Date: 02/12/14
 * Time: 11:42
 */

/** @var \entities\DiscountCode[] $discountCodes */
/** @var \entities\OrderStatus[] $orderStatuses */
/** @var \entities\PaymentMethod[] $paymentMethods */
/** @var \entities\ShippingMethod[] $shippingMethods */

/** @var \entities\StartingCost $startingCost */
/** @var \entities\Configuration $btwPercentage */
/** @var \entities\Configuration $ibanNumber */
?>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-sortable.js"></script>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Verzendmethoden</h3>
            </div>
            <table class="table table-responsive table-striped sortable">
                <thead>
                <tr>
                    <th data-defaultsort="asc" class="col-lg-10 col-md-10 col-sm-10 col-xs-10">Methode</th>
                    <th data-defaultsort="disabled" class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><i class="fa fa-edit"></i></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($shippingMethods)) {
                    foreach ($shippingMethods as $shippingMethod) {
                        ?>
                        <tr>
                            <td><?php echo $shippingMethod->getName(); ?></td>
                            <td><a href="/verzendmethodes/bewerken/<?php echo $shippingMethod->getId();?>">Bewerken</a></td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <a href="/verzendmethodes/index">Overzicht</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Overig</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        Opstart kosten: &euro;<?php echo number_format($startingCost->getPrice(), 2); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        BTW: <?php echo $btwPercentage->getValue(); ?>%
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        IBAN: <?php echo $ibanNumber->getValue(); ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <a href="/instellingen/bewerken">Bewerken</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Order status</h3>
            </div>
            <table class="table table-responsive table-striped table-orderStatuses sortable">
                <thead>
                    <tr>
                        <th data-defaultsort="asc" class="col-lg-11 col-md-11 col-sm-10 col-xs-10">Beschrijving</th>
                        <th data-defaultsort="disabled" class="col-lg-1 col-md-1 col-sm-2 col-xs-2"><i class="fa fa-edit"></i></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($orderStatuses)) {
                    foreach ($orderStatuses as $orderStatus) {
                        ?>
                        <tr>
                            <td class="col-lg-10 col-md-10 col-sm-9 col-xs-9"><?php echo $orderStatus->getDescription(); ?></td>
                            <td class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                                <a href="/orderstatussen/bewerken/<?php echo $orderStatus->getId(); ?>">
                                    Bewerken
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <a href="/orderstatussen/index">Overzicht</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Betaalmethodes</h3>
            </div>
            <table class="table table-responsive table-striped table-orderStatuses sortable">
                <thead>
                <tr>
                    <th data-defaultsort="asc" class="col-lg-11 col-md-11 col-sm-10 col-xs-10">Naam</th>
                    <th data-defaultsort="disabled" class="col-lg-1 col-md-1 col-sm-2 col-xs-2"><i class="fa fa-edit"></i></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($paymentMethods)) {
                    foreach ($paymentMethods as $paymentMethod) {
                        ?>
                        <tr>
                            <td class="col-lg-10 col-md-10 col-sm-9 col-xs-9"><?php echo $paymentMethod->getName(); ?></td>
                            <td class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                                <a href="/betaalmethodes/bewerken/<?php echo $paymentMethod->getId(); ?>">
                                    Bewerken
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <a href="/betaalmethodes/index">Overzicht</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Kortingscodes</h3>
            </div>
            <table class="table table-responsive table-striped sortable">
                <thead>
                <tr>
                    <th data-defaultsort="asc" class="col-lg-2 col-md-2 col-sm-4 col-xs-4">Code</th>
                    <th data-firstsort="asc" class="col-lg-5 col-md-4 hidden-sm hidden-xs">Beschrijving</th>
                    <th data-firstsort="asc" class="col-lg-2 col-md-1 col-sm-2 col-xs-4">Kortingsbedrag</th>
                    <th data-firstsort="asc" class="col-lg-2 col-md-4 col-sm-4 hidden-xs">Verloopdatum</th>
                    <th data-defaultsort="disabled" class="col-lg-1 col-md-1 col-sm-2 col-xs-4"><i class="fa fa-edit"></i></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($discountCodes)) {
                    foreach ($discountCodes as $discountCode) {
                        $discountAmount = null;

                        if ($discountCode->getFixedAmount() === null) {
                            $discountAmount = number_format($discountCode->getPercentage(), 2) . '%';
                        } else {
                            $discountAmount = '&euro;' . number_format($discountCode->getFixedAmount(), 2);
                        }
                        ?>
                        <tr>
                            <td><?php echo $discountCode->getCode(); ?></td>
                            <td class="hidden-xs hidden-sm"><?php echo $discountCode->getDescription(); ?></td>
                            <td><?php echo $discountAmount; ?></td>
                            <td class="hidden-xs"><?php echo $discountCode->getValidUntil()->format('d-m-Y H:i'); ?></td>
                            <td><a href="/kortingscodes/bewerken/<?php echo $discountCode->getId(); ?>">Bewerken</a></td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <a href="/kortingscodes/index">Overzicht</a>
            </div>
        </div>
    </div>
</div>