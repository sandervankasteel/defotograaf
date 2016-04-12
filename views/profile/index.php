<?php
/** @var \entities\User $view_user */
?>
<h2>Welkom <?php print($view_user->getFirstName());?></h2>
<br />
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Uw gegevens:</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Klantnummer:</th>
                            <td><?php print($view_user->getId()); ?></td>
                        </tr>
                        <tr>
                            <th>Registratie datum:</th>
                            <td><?php print($view_user->getRegDate()); ?></td>
                        </tr>
                        <tr>
                            <th>Naam:</th>
                            <td><?php print($view_user->getFirstName() . " " . $view_user->getLastName()); ?></td>
                        </tr>
                        <tr>
                            <th>Emailadres:</th>
                            <td><?php print($view_user->getEmail()); ?></td>
                        </tr>
                        <tr>
                            <th>Telefoonnummer:</th>
                            <td><?php print($view_user->getPhoneNumber()); ?></td>
                        </tr>
                        <tr>
                            <th>Adres:</th>
                            <td>
                                <?php print($view_user->getAddress()->getStreet() . " " . $view_user->getAddress()->getHouseNumber() . $view_user->getAddress()->getHouseNumberAd());?>
                                <br />
                                <?php print($view_user->getAddress()->getZipCode() . " " . $view_user->getAddress()->getCity()); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel-footer"><a href="/customers/update/<?php print($view_user->getId()); ?>">Uw gegevens bewerken</a></div>
        </div>
    </div>
</div>
<!--Joey Smit-->
<?php
// Database gegevens ophalen
$link = \services\Database::get();
?>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-sortable.js"></script>
<script type="text/javascript">
$(function () {
    $('[data-toggle="popover"]').popover({'trigger': 'hover', 'placement': 'right', html: true});
})
</script>
<?php
// Query opzetten
$query = mysqli_prepare($link, "
SELECT orders.order_id, orders.order_placed, order_status.description
FROM orders
LEFT JOIN order_status ON orders.order_status = order_status.order_status_id
WHERE orders.user_id = ? AND confirmed = 1");

$customer_id = $view_user->getId();

//Prepared statement
$query -> bind_param("i", $customer_id);

echo $link->error;

mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $order_id, $order_placed, $order_status);
?>
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Bestelling overzicht</h3></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered orderView sortable">
                <thead>
                <tr>
                    <th>Order ID</th>
                    <th data-defaultsort="asc" class="hidden-xs">Datum</th>
                    <th>Status</th>
                    <th class="text-center"><i class="fa fa-eye"></i></th>
                </tr>
                </thead>
                <?php
                //Order teller op nul
                $number_of_orders = 0;

                //Tabel met een overzicht van bestellingen van de ingelogde klant weergeven
                    while (mysqli_stmt_fetch($query)) {
                        print("<tr><td>" . $order_id . "</td><td class='hidden-xs'>" . date('d-m-Y', strtotime($order_placed)) . "</td><td>" . $order_status . "</td><td><a href='http://" . $_SERVER['SERVER_NAME'] . "/orders/view/" . $order_id . "'>Bekijk</a></td></tr>");

                        //Rows tellen
                        $number_of_orders++;
                    }
                ?>
            </table>
        </div>
    </div>
    <div class="panel-footer">
        <?php print("Aantal bestellingen: " . $number_of_orders); //Aantal bestellingen printen?>
    </div>
</div>
