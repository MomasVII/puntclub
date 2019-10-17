<?php
/* @var $navigation navigation  */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?= DESCRIPTION ?>"/>
    <meta name="author" content="<?= AUTHOR ?>" />
    <meta name="generator" content="<?= $_SERVER['HTTP_HOST']; ?>" />
    <meta name="application-name" content="<?= $_SERVER['HTTP_HOST']; ?>" />

    <title><?php echo TITLE . ' - ' . PAGE; ?></title>

    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo TITLE . ' - ' . PAGE; ?>" />
    <meta property="og:description" content="<?= DESCRIPTION ?>" />
    <meta property="og:image" content="<?= PROTOCOL.'://'.$_SERVER['HTTP_HOST']; ?>/web/image/sharelogo.jpg" />
    <meta property="og:url" content="<?= PROTOCOL.'://'.$_SERVER['HTTP_HOST']; ?>" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="<?= DESCRIPTION ?>" />
    <meta name="twitter:title" content="<?php echo TITLE; ?>" />
    <meta name="twitter:image" content="<?= PROTOCOL.'://'.$_SERVER['HTTP_HOST']; ?>/web/image/sharelogo.jpg" />

    <link rel="shortcut icon" href="<?= ROOT; ?>web/image/icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?= ROOT; ?>web/image/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= ROOT; ?>web/image/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= ROOT; ?>web/image/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= ROOT; ?>web/image/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= ROOT; ?>web/image/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= ROOT; ?>web/image/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= ROOT; ?>web/image/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= ROOT; ?>web/image/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= ROOT; ?>web/image/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= ROOT; ?>web/image/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT; ?>web/image/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= ROOT; ?>web/image/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= ROOT; ?>web/image/icon/favicon-16x16.png">

    <?= $web_compile->build_css(STYLES); ?>

    <?= $web_compile->build_js(HEAD_JS, 'head'); ?>

    <?php
        //if we're on the contact page, we want to include the recaptcha script
        if($_SERVER['PHP_SELF'] == '/contact.php'){
            echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
        }
    ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script type="text/javascript" src="<?= ROOT; ?>web/script/html5shiv.min.js"></script>
        <script type="text/javascript" src="<?= ROOT; ?>web/script/respond.min.js"></script>
    <![endif]-->
    <!--[if lte IE 9]>
        <style>
        .not_supported {
            display: block !important;
        }
        </style>
    <![endif]-->
</head>
<body class="pg-index">
    <div id="wrapper">
        <div class="bg-yello-triangle-wrapper">
            <div class="bg-yello-triangle">
                <img src="<?= ROOT; ?>web/image/yello_triangle.svg" />
            </div>
        </div>
        <div class="bg-texture"></div>
        <header class="row expanded" data-is-top="true">
            <div id="main-bar" class="row expanded main-bar">
                <div class="row">
                    <div class="brand-logo">
                        <a class="" href="/" title="Company brand name - Home"><img src="<?= ROOT; ?>web/image/rplcon_logo_small01a.svg" alt="Company brand name"/></a>
                    </div>

                    <nav id="nav-main" class="nav-bar nav-main dropdown horizontal nav-left">
                           <ul>
                              <li class="item-1 <?= $navigation->is_active('what_we_offer'); ?>"><a href="/what_we_offer.html" title="what_we_offer"> <span>what we offer</span></a></li>
                              <li class="item-2 <?= $navigation->is_active('what_we_build'); ?>"><a href="/what_we_build.html" title="what_we_build"> <span>what we build</span></a></li>
                              <li class="item-3 <?= $navigation->is_active('who_we_are'); ?>"><a href="/who_we_are.html" title="who_we_are"> <span>who we are</span></a></li>
                              <li class="item-4 <?= $navigation->is_active('document_centre'); ?>"><a href="/document_centre.html" title="document_centre"> <span>document centre</span></a></li>
                              <li class="item-5 <?= $navigation->is_active('contact'); ?>"><a href="/contact.html" title="contact"> <span>contact</span></a></li>
                           </ul>
                    </nav>
                    <nav class="nav-bar horizontal nav-right">
                        <ul class="social-list">
                            <li><a href="https://www.facebook.com/pages/category/Construction-Company/RPL-Construction-1712268448831188/" target="_blank" class="text-link" title="Follow RPL CON on Facebook"><i class="icons icons-facebook xsmall"></i></a></li>
                            <li><a href="https://www.instagram.com/rpl_construction/" target="_blank" class="text-link" title="Follow RPL CON on Instagram"><i class="icons icons-instagram xsmall"></i></a></li>
                            <li><a href="https://www.linkedin.com/company/rpl-construction-pty-ltd/" target="_blank" class="text-link" title="Follow RPL CON on Linkin"><i class="icons icons-linkedin xsmall"></i></a></li>
                        </ul>
                    </nav>
                    <button class="nav-toggle" data-target="nav-main">
                        <i class="icons icons-menu xsmall"></i>
                    </button>
                </div>
            </div>
        </header>
        <main id="content" class="content">
            <div class="not_supported">
                <h4>
                    I'm sorry your web browser is not supported we strongly recommend you use Microsoft Internet Explorer 10 or higher or an alternative browser.
                </h4>
            </div>
            <noscript>
                <div class="no_script">
                    <h1>Javascript is disabled.</h1>
                    <p>This application requires Javascript to be enabled. <br/>Javascript can be turned on in your browser settings.</p>
                </div>
            </noscript>
