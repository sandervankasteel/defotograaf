<?php
/** @var \entities\User $view_user */
/** @var string $view_page_url */
/** @var string $view_page_title */
/** @var string $view_navigation_bar_title */
?>
<html>
<head>
    <title><?php echo $view_navigation_bar_title; ?> - De fotograaf</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="revisit-after" content="7 Days" />
    <meta name="locale" content="NL" />
    <meta name="language" content="DUTCH" />
    <meta name="content-language" content="NL" />
    <meta name="REPLY-TO" content="info@defotograaf.nl" />
    <meta name="Identifier-URL" content="www.defotograaf.nl" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,700,300,600,400' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/styles/bootstrap-sort.css">
    <link rel="stylesheet" type="text/css" href="/styles/bootstrap-datepicker.css">
    <link rel="stylesheet" type="text/css" href="/styles/photo-edit.css">
    <link rel="stylesheet" type="text/css" href="/styles/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/styles/new.css">

    <script type="text/javascript" src="/scripts/jquery.js"></script>
    <script type="text/javascript" src="/scripts/bootstrap.js"></script>
    <script type="text/javascript" src="/scripts/caman/caman.full.js"></script>
    <script type="text/javascript" src="/scripts/photoupload.js"></script>
    <script type="text/javascript" src="/scripts/photoedit.js"></script>

    <link rel="stylesheet" href="http://malihu.github.io/custom-scrollbar/3.0.0/jquery.mCustomScrollbar.min.css" />
    <script src="http://malihu.github.io/custom-scrollbar/3.0.0/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript" src="/scripts/jquery-fileupload/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="/scripts/jquery-fileupload/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/scripts/jquery-fileupload/jquery.fileupload.js"></script>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <img class="img-responsive" src="/images/logo.png" alt="De Fotograaf Raalte B.V." />
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="navbar-spacing hidden-xs"></div>
                <ul class="nav navbar-nav">
                    <li class="<?php if ($view_page_url === '/index/index') { echo 'active'; } ?>">
                        <a href="/">Home</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <?php $name = ($view_user === null) ? 'Gast' : $view_user->getFirstName(); ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Welkom <?php echo $name; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php
                            if ($view_user === null) {
                                ?>
                                <li><a href="/gebruikers/inloggen">Login</a></li>
                                <li><a href="/gebruikers/registreren">Registreer</a></li>
                            <?php
                            }
                            else {
                                ?>
                                <li><a href="/profile">Profiel</a></li>
                                <li><a href="/gebruikers/uitloggen">Uitloggen</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row hidden-sm hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="pageTitle"><?php echo $view_page_title; ?> </h1>
        </div>
    </div>
    <div class="row site-content">
        <?php
        if (isset($router_warning)) {
            echo '<div class="alert alert-danger"><b>Waarschuwing: </b> er is geen alias voor deze url gedefineerd of de url gebruikt de alias niet!</div>';
        }
        ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php
            if ($view_user !== null && $view_user->getRank() === 'admin') {
                ?>
                <ul class="nav nav-pills nav-justified nav-admin">
                    <li role="presentation" <?php if ($view_page_url === '/orders/index') { echo 'class="active"'; } ?>>
                        <a href="/orders/index">Bestellingen</a>
                    </li>
                    <li role="presentation" <?php if ($view_page_url === '/customers/show') { echo 'class="active"'; } ?>>
                        <a href="/customers/show">Klanten</a>
                    </li>
                    <li role="presentation" <?php if ($view_page_url === '/settings/index') { echo 'class="active"'; } ?>>
                        <a href="/instellingen">Instellingen</a>
                    </li>
                    <li role="presentation" <?php if ($view_page_url === '/articles/show') { echo 'class="active"'; } ?>>
                        <a href="/articles/index">Artikelen</a>
                    </li>
                    <li role="presentation" <?php if (strpos($view_page_url, '/emails/show') > -1) { echo 'class="active"'; } ?>>
                        <a href="/emails">E-mails</a>
                    </li>
                </ul>
                <hr>
                <?php
            }
            ?>