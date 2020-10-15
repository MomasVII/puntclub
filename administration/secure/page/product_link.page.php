<?php require(HEAD); ?>
<div class="wrapper gray-light-bg product">

    <div class="row small-collapse">
        <div class="columns small-12">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
            <p>Below are a list of products that are currently publicly accessible.<br/>They can be linked to from the Auspost website, or third party websites using the URLs below.</p>
        </div>
    </div>

    <div class="row content-block">

        <div class="columns small-12">
            <h2 class="sub-heading gray-darker">Paid Tier</h2>
        </div>

        <div class="columns small-12">
            <?=$paid_tier_print;?>
        </div>

    </div>

    <div class="row content-block">

        <div class="columns small-12">
            <h2 class="sub-heading gray-darker">Free Tier</h2>
        </div>

        <div class="columns small-12">
            <?=$free_tier_print;?>
        </div>

    </div>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
