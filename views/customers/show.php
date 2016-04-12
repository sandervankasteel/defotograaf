<?php
/**
 * Created by PhpStorm.
 * User: Tim Oosterbroek
 * Date: 26-11-2014
 * Time: 11:27
 */
?>
<div class="container">
    <div class="row">
        <div id="custom-search-input">
            <div class="input-group col-lg-4 col-md-4 col-sm-8 col-xs-12">
                <form role="form" action="" method="POST">
                    <input type="text" name="searchField" class="search-query form-control" placeholder="Zoek op naam, adres, postcode, woonplaats email of telefoonnummer" >
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

if (isset($_POST['searchButton'])) {
$link = \services\Database::GET();
$search_term = ucfirst($_POST["searchField"]);
$search_term_2 = "%" . ucfirst($_POST["searchField"]) . "%";
$stmt = mysqli_prepare($link, '
        SELECT DISTINCT C.user_id, C.firstname, C.lastname, C.phonenumber, C.email, A.street, A.house_number, A.house_number_ad, A.zipcode, A.city
        FROM users C
        LEFT JOIN addresses A ON  C.address = A.address_id
        WHERE C.user_id = ? OR C.firstname LIKE ? OR C.lastname LIKE ? OR C.phonenumber like ? OR C.email like ? OR A.street LIKE ? OR A.house_number LIKE ?
        OR A.house_number_ad LIKE ? OR A.zipcode like ? OR A.city LIKE ?');

echo $link->error;

mysqli_stmt_bind_param($stmt, "issississs", $search_term, $search_term_2, $search_term_2, $search_term_2, $search_term_2, $search_term_2, $search_term_2, $search_term_2, $search_term_2,$search_term_2);

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $user_id, $firstname, $lastname, $phonenumber, $email, $street, $house_number, $house_number_ad, $zipcode, $city);


echo $link->error;

?>
<h4>Zoekresultaten:</h4>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php if (!empty($search_term)) {
                    print("Zoek resultaat voor: \"" . $search_term . "\"");
                } else {
                    print("Geen zoekopdracht gegeven");
                } ?></h3></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered orderView sortable">
                    <thead>
                    <tr>
                        <th>Klant ID</th>
                        <th>Naam</th>
                        <th class="hidden-xs">Adres</th>
                        <th class="hidden-xs hidden-md">Postcode</th>
                        <th data-defaultsort="asc" class="hidden-xs hidden-sm">Plaats</th>
                        <th>Email</th>
                        <th class="text-center"><i class="fa fa-edit"></i></th>
                    </tr>
                    </thead>
                    <?php
                    //Tabel met een overzicht van bestellingen weergeven
                    while (mysqli_stmt_fetch($stmt)) {
                        print("<tr><td>" . $user_id ."</td><td>" . $firstname . " " . $lastname . "</td><td class='hidden-xs'>" . $street . " " . $house_number . " " . $house_number_ad . "</td><td class='hidden-xs hidden-md'>"  .$zipcode ."</td><td class='hidden-xs hidden-md'>"  .$city ."</td><td class='hidden-xs hidden-md'>"  .$email ."</td><td class='text-center'><a href='http://" . $_SERVER['SERVER_NAME'] . "/customers/update/" . $user_id . "'>Bewerk</a></td></tr>");
                    }

                    mysqli_stmt_close($stmt);
                    ?>
                </table>
            </div>
        </div>
        <div class="panel-footer text-right">
            <a href="/customers/show/"><input class="btn btn-yellow" type="submit" name="submit" value="Terug naar overzicht"/></a>
        </div>
    <?php

                        /** @var \entities\User $customer */
                        }else
                        {
                            ?>

        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Klanten overzicht</h3></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered orderView">
                        <thead>
                        <tr>
                            <th>Klant ID</th>
                            <th>Naam</th>
                            <th>Adres</th>
                            <th>Postcode</th>
                            <th class="hidden-xs">Woonplaats</th>
                            <th class="hidden-xs hidden-sm">Email</th>
                            <th class="text-center"><i class="fa fa-edit"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php

                    foreach ($customers as $customer) {
                        $address = $customer->getAddress();
                        ?>
                        <tr>
                            <td><?php echo $customer->getId(); ?></td>
                            <td><?php echo $customer->getFirstName() . " " . $customer->getLastName(); ?></td>
                            <td><?php echo $address->getStreet() . " " . $address->getHouseNumber() . $address->getHouseNumberAd(); ?></td>
                            <td><?php echo $address->getZipCode(); ?></td>
                            <td class="hidden-xs"><?php echo $address->getCity(); ?></td>
                            <td class="hidden-xs hidden-sm"><?php echo $customer->getEmail(); ?></td>
                            <td><p><a href="update/<?php echo $customer->getId(); ?>">Bewerk</a></p></td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
