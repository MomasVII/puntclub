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
    <link rel="shortcut icon" href="<?= ROOT; ?>web/image/favicon.ico" />

    <?= $web_compile->build_css(STYLES); ?>

    <?= $web_compile->build_js(HEAD_JS, 'head'); ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script type="text/javascript" src="<?= ROOT; ?>web/script/html5shiv.min.js"></script>
        <script type="text/javascript" src="<?= ROOT; ?>web/script/respond.min.js"></script>
    <![endif]-->
</head>
<body class="pg-<?= $navigation->get_link_by_url('navigation')['label']; ?>">
    <div id="wrapper">
        <header class="row expanded">

            <div id="top-bar" class="row expanded top-bar">
                <div class="row collapse">
                    <div class="columns small-6">
                        <ul class="social-list">
                            <li><a href="#" target="_blank" class="text-link"><i class="icons icons-twitter xsmall"></i></a></li>
                            <li><a href="#" target="_blank" class="text-link"><i class="icons icons-facebook xsmall"></i></a></li>
                            <li><a href="#" target="_blank" class="text-link"><i class="icons icons-instagram xsmall"></i></a></li>
                        </ul>
                    </div>
                    <div class="columns small-6 text-right">
                        <a href="#" class="button small" title="Subscribe with us">Subscribe</a> <a href="#" class="button small" title="Sign in to your account">Sign In</a>
                    </div>
                </div>
            </div>
            <div id="main-bar" class="row expanded main-bar">
                <div class="row">
                    <div class="brand-logo">
                        <a class="" href="/" title="Company brand name - Home"><img src="<?= ROOT; ?>web/image/demo/rare_logo.png" alt="Company brand name"></a>
                    </div>
                    <nav id="nav-main" class="nav-bar nav-main dropdown horizontal nav-right">
                        <?php echo $navigation->make_from_db('navigation') ?>
                    </nav>
                    <button class="nav-toggle" data-target="nav-main">
                        <i class="icons icons-close xsmall"></i>
                    </button>
                </div>
            </div>
        </header>
        <main id="content" class="content">
            <noscript>
                <div class="no_script">
                    <h1>Javascript is disabled.</h1>
                    <p>This application requires Javascript to be enabled. <br/>Javascript can be turned on in your browser settings.</p>
                </div>
            </noscript>
