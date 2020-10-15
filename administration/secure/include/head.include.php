<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="DC.Creator" scheme="AglsAgent" content="Australia Post"/>
    <meta name="DC.Title" content="Australia Post - Home"/>

    <title><?php echo TITLE.' - '.PAGE;?></title>
    <meta name="description" content="<?=DESCRIPTION ?>"/>
    <meta name="author" content="<?=AUTHOR ?>">
    <meta name="generator" content="<?=$_SERVER['HTTP_HOST'];?>">
    <meta name="application-name" content="<?=$_SERVER['HTTP_HOST'];?>-v2">
	<meta name="theme-color" content="#d60926">
	<link href="<?=LOCAL;?>manifest.json" rel="manifest">

	<link rel="apple-touch-icon" sizes="180x180" href="<?=LOCAL;?>/web/image/icon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?=LOCAL;?>/web/image/icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?=LOCAL;?>/web/image/icon/favicon-16x16.png">
	<link rel="mask-icon" href="<?=LOCAL;?>/web/image/icon/safari-pinned-tab.svg" color="#d60926">

	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo TITLE . ' - ' . PAGE; ?>" />
	<meta property="og:description" content="<?= DESCRIPTION ?>" />
	<meta property="og:image" content="<?= PROTOCOL.'://'.$_SERVER['HTTP_HOST']; ?>/web/image/auspost_sharelogo.jpg" />
	<meta property="og:url" content="<?= PROTOCOL.'://'.$_SERVER['HTTP_HOST']; ?>" />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="<?= DESCRIPTION ?>" />
	<meta name="twitter:title" content="<?php echo TITLE; ?>" />
	<meta name="twitter:image" content="<?= PROTOCOL.'://'.$_SERVER['HTTP_HOST']; ?>/web/image/auspost_sharelogo.jpg" />

    <?=STYLES ?>

    <?=HEAD_JS ?>

	<script>
	    (function(h,o,t,j,a,r){
	        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
	        h._hjSettings={hjid:1476958,hjsv:6};
	        a=o.getElementsByTagName('head')[0];
	        r=o.createElement('script');r.async=1;
	        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
	        a.appendChild(r);
	    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
	</script>
</head>
<body>

<header>
    <div id="top-bar" class="row expanded top-bar">
        <div class="row">
            <div class="brand-logo">
                <a class="" href="/" title="<?php echo TITLE.' - '.PAGE;?>">
                    <img src="<?=LOCAL;?>web/image/svg/auspost-logo01a.svg" alt="Australia Post logo"/>
                </a>
                <h3><?=TITLE;?></h3>
            </div>
        </div>
    </div>
    <div id="main-bar" class="row expanded main-bar">
        <div class="row">
            <nav id="nav-main" class="nav-bar nav-main dropdown horizontal nav-right">
                <ul>
                    <li id="nav-subscribers"><a href="<?=LOCAL;?>subscriber.html" title="Manage Subscribers">Subscribers</a></li>
                    <li id="nav-products"><a href="<?=LOCAL;?>product.html" title="Manage Products">Products</a></li>
					<?php if (isset($_SESSION['access']) && !empty($_SESSION['access']) && $_SESSION['access'] === 'admin') { ?>
	                    <li id="nav-users"><a href="<?=LOCAL;?>manage_users.html" title="AP Team">Manage Users</a></li>
					<?php } ?>
                    <li id="nav-report"><a href="<?=LOCAL;?>report.html" title="Reporting and Analytics">Reports</a></li>
                    <li id="logout"><a href="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>?sign_out=1">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
