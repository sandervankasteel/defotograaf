<?php
session_start(); // Sessie starten

// Sessie ID genereren functie
function generateRandomString($length = 3) {
    global $a;
    global $i;
    global $z;
    $z=0;
    $a=0;
    while ($a < 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        if (is_dir("mediaupload/" . $randomString) != "mediaupload/" . $randomString) {//check of de randomstring al bestaat zo niet teruggeven anders opnieuw beginnen
            $a++;
            return $randomString;
        }
        elseif($z>1000) {//zorgen dat de loop niet infinite wordt zodat PHP geen timeout error geeft
            print('Er zijn geen beschikbare mappen meer, neem contact op met de beheerder van de site.');
            die;
        }

        else{//loop om te zorgen dat er meerdere keren geprobeerd wordt om een randomstring te genereren
            $i=0;
            $z++;
        }
    }
}

// Als er nog geen sessie ID is, dan word deze gegenereerd met de function en weggezet in een $_SESSION
if (empty($_SESSION["SESSID"])) {
    $_SESSION["SESSID"] = generateRandomString();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Foto's uploaden</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles/style.css" type="text/css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,700,300,600,400' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <header>
            <div class="wrap">
                <div class="headerLogo">
                    <a href="http://defotograaf.nl/"><img src="images/logo.png" alt="De Fotograaf Raalte B.V." /></a>
                </div>
            </div>
        </header>
        <h1 class="pageTitle">Foto's uploaden</h1>
        <section id="content">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                <input id="submit" class="small-button" type="submit" name="submit" value="submit" disabled />
            </form>
            <script>
                // Als er geen bestand is geselecteerd dan is de upload knop disabled (met dank aan Remco)
                $('#file').on('change', function() {
                    if (this.files.length > 0) {
                        $('#submit').removeAttr('disabled');
                    }
                });
            </script>
            <?php

            $link = mysqli_connect("localhost", "root", "password", "de_fotograaf");

            // Functie voor het resizen van alle afbeeldingen naar een thumb mapje
            function imageResize($file, $path, $height, $width) {
                $target = "/home/sander/sites/sandervankasteel.nl/garden/public/mediaupload/" . $_SESSION["SESSID"] . "/thumbs/";
                $handle = opendir($path);
                if($file != "." && $file != ".." && !is_dir($path.$file))
                {
                    $thumb = $path.$file;
                    $imageDetails = getimagesize($thumb);
                    $originalWidth = $imageDetails[0];
                    $originalHeight = $imageDetails[1];
                    if($originalWidth > $originalHeight) {
                        $thumbHeight = $height;
                        $thumbWidth = ($originalWidth/($originalHeight/$thumbHeight));
                    } else {
                        $thumbWidth = $width;
                        $thumbHeight = ($originalHeight/($originalWidth/$thumbWidth));
                    }
                    if (in_array("image/jpeg", getimagesize($thumb))) {
                        $originalImage = ImageCreateFromJPEG($thumb);

                    }
                    if (in_array("image/png", getimagesize($thumb))) {
                        $originalImage = ImageCreateFromPNG($thumb);
                    }
                    $thumbImage = ImageCreateTrueColor($thumbWidth, $thumbHeight);
                    imagecopyresized($thumbImage, $originalImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $originalWidth, $originalHeight);
                    $filename = $file;
                    if (in_array("image/jpeg", getimagesize($thumb))) {
                        $originalImage = ImageCreateFromJPEG($thumb);
                        imagejpeg($thumbImage, $target.$filename, 100);
                    }
                    if (in_array("image/png", getimagesize($thumb))) {
                        $originalImage = ImageCreateFromPNG($thumb);
                        imagepng($thumbImage, $target.$filename, null, 100);
                    }
                }
                closedir($handle);
            }

            if(isset($_FILES['files'])) {
                $errors = array();
                $image_uploads = array();

                foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {

                    $file_name = $key . "_" . $_FILES['files']['name'][$key];
                    $file_size = $_FILES['files']['size'][$key];
                    $file_tmp = $_FILES['files']['tmp_name'][$key];
                    $file_type = $_FILES['files']['type'][$key];
                    $number_of_photos = count($_FILES['files']['name']);
                    $file_check = $_FILES['files']['name'][$key];
                    // Een bestand mag maximaal xx bytes zijn
                    $max_file_size = 1024 * 10240; //2MB (De berekening is in bytes)

                    // Controleren of een bestand wel een foto is
                    if (getimagesize($file_tmp) == FALSE) {
                        $errors[] = 'Er mogen enkel JPG, JPEG of PNG bestanden geupload worden!';
                    } else {
                        if (!in_array("image/jpeg", getimagesize($file_tmp)) && !in_array("image/png", getimagesize($file_tmp))) {
                        }
                    }
                    // Als het bestand te groot is krijg je de volgende melding
                    if ($file_size > $max_file_size) {
                        $errors[] = 'Een foto mag niet groter zijn dan 10MB';
                    }
                    // Hieronder kun je aangeven hoeveel foto's er maximaal geupload mogen worden
                    if($number_of_photos > 19) { // PHP.ini heeft een limiet van 20 foto's.
                        $errors[] = 'Er mogen maximaal 19 fotos tegelijk worden geupload';
                    }

                    $query = mysqli_prepare($link, "INSERT INTO photos (filename) VALUES('$file_name')");

                    $upload_folder = "/home/sander/sites/sandervankasteel.nl/garden/public/mediaupload/" . $_SESSION["SESSID"];
                    $thumb_folder = "/home/sander/sites/sandervankasteel.nl/garden/public/mediaupload/" . $_SESSION["SESSID"] . "/thumbs/";

                    // Als er geen errors zijn gevonden dan voert hij het onderstaande uit
                    if (empty($errors) == true) {
                        // Map aanmaken als deze nog niet bestaat
                        if (is_dir($upload_folder) == false && is_dir($thumb_folder) == false) {
                            mkdir("$upload_folder", 0700);
                            mkdir("$thumb_folder", 0700);
                        }
                        // Kijken of het bestand al bestaat, zo nee dan uploaden.
                        if (is_dir("$upload_folder/" . $file_name) == false && is_dir("$thumb_folder/" . $file_name) == false) {
                            move_uploaded_file($file_tmp, "$upload_folder/" . $file_name);

//                            copy($upload_folder . "/" . $file_name, $thumb_folder . $file_name);


                        }
                        mysqli_stmt_execute($query);
                        // Bestandslocaties opslaan in array (Volledige grootte, niet de thumbs)
                       // $image_uploads[] = $img_url = "mediaupload/" . $_SESSION["SESSID"] . "/" . $file_name; // Alle geuploade afbeeldingen in een array stoppen
                        // Thumbnails printen
                        print ("<img style='padding: 25px;' src='/mediaupload/" . $_SESSION["SESSID"] . "/thumbs/" . $file_name . "'/> "); // Alle geuploade afbeeldingen laten zien
                    }
                }
                $source = $upload_folder;
                $directory = opendir($source);
                $temp = $file_name;
                //Scan through the folder one file at a time
                while(($temp = readdir($directory)) != false)
                {
                    //Run each file through the image resize function
                    imageResize($temp, $source.'/', 150, 150);
                }
                // Errors printen, als die er zijn.
                if (!empty($errors)) {
                    print ($errors[0]);
                }

                header("Location: /PhotoEdit/index/" . $_SESSION["SESSID"]);
            }
            if (!empty($image_uploads)) {
                echo "<br />";
                print_r($image_uploads);
            }
            ?>
        </section>
        <footer>
                <div class="wrap">
                    <section class="footerLeft">
                        <h1>Contact</h1>
                        <span class="bigText">T: 0572-354637</span>
                        <p>
                            De Fotograaf Raalte B.V.
                            <br />
                            Varkensmarkt 9b
                            <br />
                            8102 EG Raalte
                            <br />
                        </p>
                    </section>
                    <section class="footerMiddle">
                        <h1>De Fotograaf Raalte</h1>
                        <p>
                            Bij De Fotograaf Raalte zijn je foto’s in goede handen: wat afdrukken
                            van foto’s betreft zijn wij meermaals als beste van Nederland getest.
                        </p>
                        <br />
                        <p>
                            Op de website van de Fotograaf Raalte kan je
                            foto’s van je digitale bestanden laten afdrukken, kan je van een
                            gemaakte fotoshoot proeffoto’s bekijken en vergrotingen bestellen,
                            en kan je voorbeeldfoto’s bekijken van studiofoto’s, trouwfoto’s en
                            schoolfoto’s.
                        </p>
                    </section>
                    <section class="footerRight">
                        <h1>Copyright</h1>
                        <p>
                            Afbeeldingen en teksten op deze website zijn
                            beschermd met auteursrecht. Afbeeldingen en teksten
                            mogen niet zonder toestemming gebruikt worden.
                        </p>
                        <br />
                        <p>
                            Copyright 2014 De Fotograaf Raalte B.V
                        </p>
                    </section><div class="clear"></div>
                </div>
            </footer>
    </body>
</html>