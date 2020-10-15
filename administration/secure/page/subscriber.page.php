<?php require(HEAD); ?>

<div class="wrapper gray-light-bg subscriber">

    <div class="row small-collapse">
        <div class="columns small-12">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
        </div>
    </div>

    <div class="row content-block">

        <form data-abide novalidate id="search-form" action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>" class="form" method="get">

            <div class="columns small-12 search-form">

                <label class="item">Search
                    <input id="search" maxlength="60" name="keyword" tabindex="1" type="text" placeholder="Keyword"/>
                </label>

                <label class="item">Based on
                    <select name="field">
                        <option value="all" selected="selected">All Fields</option>
                        <option value="paid_reference">Payment Reference</option>
                        <option value="email_address">Email Address</option>
                        <option value="business_name">Business Name</option>
                        <option value="business_abn">Business ABN/ACN</option>
                        <option value="contact_name">Contact Name</option>
                    </select>
                </label>

                <button class="item button btn-gray <?php if($expired != 1){echo 'selected';} else {echo 'deselected';} ?>" type="submit">Current ></button>

                <button class="item button btn-gray <?php if($expired === 1){echo 'selected';} else {echo 'deselected';}  ?>" type="submit" name="expired" value="1">Expired ></button>

                <a class="item button btn-gray" href="subscriber_insert.html">+ Add subscriber</a>

            </div>

            <?=$pending_response;?>

            <div class="columns small-12">
                <div id="response" data-abide-error class="alert callout" style="display: none;">
                    <p><i class="fi-alert"></i> <?=$response;?> </p>
                </div>
            </div>

            <div class="columns small-12">
                <?=$record_print;?>
            </div>

            <div class="columns small-12">
                <?=$pagination_print;?>
            </div>

        </form>




    </div>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
