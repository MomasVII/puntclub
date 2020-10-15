<?php require(HEAD); ?>
<div class="wrapper gray-light-bg product">

    <div class="row small-collapse">
        <div class="columns small-6">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
        </div>
        <div class="columns small-6">
            <a href="<?=LOCAL;?>product_insert.html" class="button page-title btn-green float-right">+ Add product</a>
            <a href="<?=LOCAL;?>product_link.html" class="button page-title btn-green float-right">Product links</a>
        </div>
    </div>

    <div class="row small-collapse">

        <?=$record_print;?>

    </div>

    <?=$pagination_print;?>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
