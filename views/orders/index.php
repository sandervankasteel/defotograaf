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
<div class="container">
    <div class="row">
        <div id="custom-search-input">
            <div class="input-group col-lg-4 col-md-4 col-sm-8 col-xs-12">
                <form role="form" action="" method="POST">
                    <input type="text" name="searchField" class="search-query form-control" placeholder="Zoek op order ID, klant ID, voornaam of achternaam" />
                    <br />
                    <br />
                    <button class="btn btn-yellow" name="searchButton" type="submit">
                        <i class="fa fa-search"></i> Zoek
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
// Zoek query opbouwen
    if (isset($_POST['searchButton'])) {
        $search_term = $_POST["searchField"];
        $search_term_2 = "%" . $_POST["searchField"] . "%";
        $query = mysqli_prepare($link, "
        SELECT DISTINCT orders.order_id, orders.user_id, users.firstname, users.lastname, orders.order_placed, order_status.description
        FROM orders
        LEFT JOIN order_status ON orders.order_status = order_status.order_status_id
        LEFT JOIN users ON orders.user_id = users.user_id
        WHERE confirmed = 1 AND (orders.order_id = ?
        OR orders.user_id = ?
        OR users.firstname LIKE ?
        OR users.lastname LIKE ?)");

        echo $link->error;

        //Prepared statement klaarzetten
        $query -> bind_param("iiss", $search_term, $search_term, $search_term_2, $search_term_2);

        mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $order_id, $customer_id, $firstname, $lastname, $order_placed, $order_status);
        ?>
        <h4>Zoekresultaten:</h4>
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php if(!empty($search_term)) { print("Zoek resultaat voor: \"" . $search_term . "\"");} else { print("Geen zoekopdracht gegeven");}?></h3></div>
        <div class="panel-body">
        <?php
        if (!empty($_POST["searchField"])) {
            ?>
            <div class="table-responsive">
                <table class="table table-bordered orderView">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th class="hidden-xs">Klant ID</th>
                        <th class="hidden-xs hidden-md">Naam</th>
                        <th class="hidden-xs hidden-sm">Datum</th>
                        <th>Status</th>
                        <th class="text-center"><i class="fa fa-edit"></i></th>
                    </tr>
                    </thead>
                    <?php
                    //Uitvoeren van zoekresultaten
                    while (mysqli_stmt_fetch($query)) {
                        print("<tr><td>" . $order_id . "</td><td class='hidden-xs'>" . $customer_id . "</td><td class='hidden-xs hidden-md'>" . $firstname . " " . $lastname . "</td></td><td class='hidden-xs hidden-sm'>" . date('d-m-Y', strtotime($order_placed)) . "</td><td>" . $order_status . "</td><td class='text-center'><a href='http://" . $_SERVER['SERVER_NAME'] . "/orders/view/" . $order_id . "'>Bewerk</a></td></tr>");
                    }
                    ?>
                </table>
            </div>
        <?php
        }
        ?>
        </div>
            <div class="panel-footer text-right">
                <a href="/orders/"><input class="btn btn-yellow" type="submit" name="submit" value="Terug naar overzicht"/></a>
            </div>
        </div>
        <?php
    } else {
        // Query opzetten
        $query = mysqli_prepare($link, "
        SELECT orders.order_id, orders.user_id, users.firstname, users.lastname, orders.order_placed, order_status.description
        FROM orders
        LEFT JOIN order_status ON orders.order_status = order_status.order_status_id
        LEFT JOIN users ON orders.user_id = users.user_id
        WHERE confirmed = 1
        ORDER BY orders.order_id DESC");

        echo $link->error;

        
        mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $order_id, $customer_id, $firstname, $lastname, $order_placed, $order_status);
?>
    <br/>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Bestelling overzicht</h3></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered orderView sortable">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th class="hidden-xs">Klant ID</th>
                        <th class="hidden-xs hidden-md">Naam</th>
                        <th data-defaultsort="asc" class="hidden-xs hidden-sm">Datum</th>
                        <th>Status</th>
                        <th class="text-center"><i class="fa fa-edit"></i></th>
                    </tr>
                    </thead>
                    <?php
                    //Order teller op nul
                    $number_of_orders = 0;

                    //Tabel met een overzicht van bestellingen weergeven
                    while (mysqli_stmt_fetch($query)) {
                        print("<tr><td>" . $order_id . "</td><td class='hidden-xs'>" . $customer_id . "</td><td class='hidden-xs hidden-md'>" . $firstname . " " . $lastname . "</td></td><td class='hidden-xs hidden-sm'>" . date('d-m-Y', strtotime($order_placed)) . "</td><td>" . $order_status . "</td><td><a href='http://" . $_SERVER['SERVER_NAME'] . "/orders/view/" . $order_id . "'>Bewerk</a></td></tr>");

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
<?php
}
?>