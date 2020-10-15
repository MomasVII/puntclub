<?php require(HEAD); ?>

<div class="wrapper gray-light-bg manage-users">

    <div class="row small-collapse">
        <div class="columns small-6">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
        </div>
        <div class="columns small-6">
            <a href="<?=LOCAL;?>user_insert.html" class="button page-title btn-green float-right">+ Add user</a>
        </div>
    </div>

    <div class="row content-block">

        <div class="columns small-12">
            <div id="response" data-abide-error class="alert callout" style="display: none;">
                <p><i class="fi-alert"></i> <?=$response;?> </p>
            </div>
        </div>

        <div class="columns small-12">

            <div style="display: block; position: relative; margin: 0; padding: 0; text-align: left;">

                <div class="flex-row">
                    <div class="item head">Date Added</div>
                    <div class="item head">Name</div>
                    <div class="item head">Email Address</div>
                    <div class="item head">Access</div>
                </div>

                <?= $users_print; ?>

            </div>

        </div>

    </div>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
