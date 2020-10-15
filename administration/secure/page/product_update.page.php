<?php require(HEAD); ?>

<div class="wrapper gray-light-bg">

    <form data-abide novalidate role="form" action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>?id=<?=$_GET['id'];?>" method="post" enctype="multipart/form-data" id="insert">

		<div id="waitSpinner" class="spinner-wrapper">
			<div class="spinner-mask">
				<div class="spinner-container">
					<div class="spinner"></div>
				</div>
			</div>
		</div>

        <div class="row small-collapse">
            <div class="columns small-6">
                <h1 class="gray-darker page-title"><?=PAGE;?></h1>
            </div>
            <div class="columns small-6">
                <a href="<?=LOCAL;?>product.html" class="button page-title btn-gray float-right">Return to Product List</a>
            </div>
        </div>

        <div class="row content-block in-flow">

            <div class="columns small-12">
                <div id="response" data-abide-error class="alert callout" style="display: none;">
                    <p><i class="fi-alert"></i> <?=$response;?> </p>
                </div>
            </div>

			<div class="columns small-12">
                <p>
                    <small>* Required fields</small>
                </p>
            </div>

            <div class="columns small-12 medium-6">

                <label for="productName">Product name&nbsp;*
                    <input id="productName" type="text" name="name" required  value="<?=$product_data['content']['name'];?>"/>
                </label>

                <label for="SKU">SKU&nbsp;*
                    <input id="SKU" type="text" name="sku" required pattern="alpha_numeric" value="<?=$product_data['content']['sku'];?>" />
                </label>

            </div>
            <div class="columns small-12 medium-6">

                <label for="subscriptionTerms">Subscription Term (months)&nbsp;*
                    <input id="subscriptionTerms" type="text" name="subscription_term" required pattern="number" value="<?=$product_data['content']['subscription_term'];?>" />
                </label>

            </div>

            <div class="columns small-12"></div>

            <div class="columns small-12 medium-6">

                <fieldset class="file-upload-2">
                    <label>Upload header image</label>
                    <input type="file" name="header_image" id="headerFile" class="inputfile"  />
                    <label for="headerFile">
                        <span>Please choose a 1080px x 314px PNG image</span>
                        <strong>Browse</strong>
                    </label>
                </fieldset>

            </div>
            <div class="columns small-12 medium-6">

                <fieldset class="file-upload-2">
                    <label>Upload thumbnail image</label>
                    <input type="file" name="thumbnail_image" id="thumbnailFile" class="inputfile" />
                    <label for="thumbnailFile">
                        <span>Please choose a 150px x 150px PNG image</span>
                        <strong>Browse</strong>
                    </label>
                </fieldset>

            </div>

            <div class="columns small-12">
                <h2 class="sub-heading gray-darker">Add Paid Tier product data</h2>
            </div>

            <div class="columns small-12 medium-6">

                <fieldset class="file-upload-2">

                    <label class="main-label">Upload product data</label>
                    <input type="file" name="paid_tier_file" id="paidFile" class="inputfile" data-multiple-caption="{count} files selected" multiple />
                    <label for="paidFile">
                        <span>Please choose a CSV spreadsheet</span>
                        <strong>Browse</strong>
                    </label>

                </fieldset>

            </div>
            <div class="columns small-12 medium-6">
                <fieldset class="un-indent">
                    <legend>Append/replace existing data</legend>
                    <input type="radio" name="append_replace" value="append" id="appendData"><label for="appendData">Append to existing data</label>
                    <input type="radio" name="append_replace" value="replace" id="replaceData"><label for="replaceData">Replace existing data</label>
                </fieldset>
            </div>

            <div class="columns small-12">
                    <?='<a href="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'?id='.$_GET['id'].'&download=existing" class="button btn-green" title="Download Current Paid File">Download existing data</a>'; ?>
            </div>

            <div class="columns small-12">
                <fieldset class="un-indent">
                    <input id="published" name="published" type="checkbox" <?php if ($product_data['content']['published'] === 1) {echo 'checked="checked"';} ?>><label for="published" >Publish Product</label>
                    <input id="enable_free_tier" name="enable_free_tier" type="checkbox" <?php if ($product_data['content']['enable_free_tier'] === 1) {echo 'checked="checked"';} ?>><label for="enable_free_tier">Enable Free Tier</label>
                    <input id="enforce_delta_delivery" name="enforce_delta_delivery" type="checkbox" <?php if ($product_data['content']['enforce_delta_delivery'] === 1) {echo 'checked="checked"';} ?>><label for="enforce_delta_delivery">Deliver product as delta</label>
                    <input id="disable_attachment" name="disable_attachment_delivery" type="checkbox" <?php if ($product_data['content']['disable_attachment_delivery'] === 1) {echo 'checked="checked"';} ?>><label for="disable_attachment">Disable attachment delivery</label>
                </fieldset>
            </div>

            <div class="free_tier">

                <div class="columns small-12">
                    <h2 class="sub-heading gray-darker">Add Free Tier product file</h2>
                </div>

                <div class="columns small-12 medium-6">
                    <fieldset class="file-upload-2">
                        <label>Upload free file</label>
                        <input type="file" name="free_tier_file" id="freeFile" class="inputfile" />
                        <label for="freeFile">
                            <span>Please choose a PDF document</span>
                            <strong>Browse</strong>
                        </label>
                    </fieldset>
                </div>
                <div class="columns small-12 medium-6"></div>
            </div>


        </div>

        <div class="row small-collapse">
            <div class="columns small-12">
                <h1 class="gray-darker page-title"><?=PAGE;?> pricing</h1>
            </div>
        </div>

        <div class="row content-block relative">

            <div class="columns small-6 relative end" style="z-index:1">
                <h2 class="sub-heading gray-darker">National</h2>
                <fieldset class="un-indent inline">
                    <label for="nationalEnabled">
                        <input type="checkbox" id="nationalEnabled" data-target="national-accordion" class="accordionEnabler" name="enabled[national][filter]" <?php if ($product_data['content']['national_filter_enabled'] === 1) {echo 'checked="checked"';} ?>>
                        Enable National Pricing
                    </label>
                </fieldset>
            </div>

            <ul id="national-accordion" class="accordion columns small-12 product-accordion" data-accordion data-allow-all-closed="true">
                <li class="accordion-item" data-accordion-item>

                    <a href="#national-accordion-content" class="accordion-title" data-target="nationalEnabled"></a>

                    <div id="national-accordion-content" class="accordion-content row" data-tab-content>

                        <div class="columns small-4 enableContainer" aria-expanded="false">
                            <label for="nationalOnceOffEnabled">
                                <input type="checkbox" id="nationalOnceOffEnabled" class="enableBox" name="enabled[national][onceoff]" <?php if ($product_data['content']['national_onceoff_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                Enable once off purchase
                            </label>
                            <label for="nationalOnceOffPrice" class="enableItem">
                                Once off price
                                <input type="text" placeholder="$" id="nationalOnceOffPrice" name="price[national][onceoff]" value="<?php echo sprintf("%01.2f", $product_data['content']['national_onceoff_price']); ?>" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)"/>
                            </label>
                        </div>
                        <div class="columns small-4 enableContainer" aria-expanded="false">
                            <label for="nationalQuarterlyEnabled">
                                <input type="checkbox" id="nationalQuarterlyEnabled" class="enableBox" name="enabled[national][quarterly]" <?php if ($product_data['content']['national_quarterly_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                Enable quarterly purchase
                            </label>
                            <label for="nationalQuarterlyPrice" class="enableItem">
                                Quarterly price
                                <input type="text" placeholder="$" id="nationalQuarterlyPrice" name="price[national][quarterly]" value="<?=sprintf("%01.2f", $product_data['content']['national_quarterly_price']); ?>" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                            </label>
                        </div>
                        <div class="columns small-4 end enableContainer" aria-expanded="false">
                            <label for="nationalMonthlyEnabled">
                                <input type="checkbox" id="nationalMonthlyEnabled" class="enableBox" name="enabled[national][monthly]" <?php if ($product_data['content']['national_monthly_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                Enable monthly purchase
                            </label>
                            <label for="nationalMonthlyPrice" class="enableItem">
                                Monthly price
                                <input type="text" placeholder="$" id="nationalMonthlyPrice" name="price[national][monthly]" value="<?=sprintf("%01.2f", $product_data['content']['national_monthly_price']);?>" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                            </label>
                        </div>

                    </div>

                </li>
            </ul>

        </div>

        <div class="row content-block relative">

            <div class="columns small-6 relative end" style="z-index:1">
                <h2 class="sub-heading gray-darker">State</h2>
                <fieldset class="un-indent inline">
                    <label for="stateEnabled"><input type="checkbox" id="stateEnabled" data-target="state-accordion" class="accordionEnabler" name="enabled[state][filter]" <?php if ($product_data['content']['state_filter_enabled'] === 1) {echo 'checked="checked"';} ?>>Enable State Pricing</label>
                </fieldset>
            </div>

            <ul id="state-accordion" class="accordion columns small-12 product-accordion" data-accordion data-allow-all-closed="true">
                <li class="accordion-item" data-accordion-item>

                    <a href="#state-accordion-content" class="accordion-title" data-target="stateEnabled"></a>

                    <div id="state-accordion-content" class="accordion-content" data-tab-content>

                        <div class="row">

                            <div id="stateTagBox" class="tag-box columns small-6 end">
                                <div class="input-group">
                                    <select class="input-group-field input-field">
                                        <?=$state_field_select;?>
                                    </select>
                                    <div class="input-group-button">
                                        <button type="button" class="button" value="add">+ Add</button>
                                    </div>
                                </div>
                                <input type="text" id="state_filter" class="hide value-field" name="filter[state]" value="<?=$state_haystack_active;?>" />

                                <div class="tag-list"></div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="columns small-4 enableContainer" aria-expanded="false">

                                <label for="stateOnceOffEnabled">
                                    <input type="checkbox" id="stateOnceOffEnabled" class="enableBox" name="enabled[state][onceoff]" <?php if ($product_data['content']['state_onceoff_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                    Enable once off purchase
                                </label>

                                <div class="enableItem">
                                    <?=$state_onceoff_price;?>
                                </div>
                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">

                                <label for="stateQuarterlyEnabled">
                                    <input type="checkbox" id="stateQuarterlyEnabled" class="enableBox" name="enabled[state][quarterly]" <?php if ($product_data['content']['state_quarterly_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                    Enable quarterly purchase
                                </label>

                                <div class="enableItem">
                                    <?=$state_quarterly_price;?>
                                </div>

                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">

                                <label for="stateMonthlyEnabled">
                                    <input type="checkbox" id="stateMonthlyEnabled" class="enableBox" name="enabled[state][monthly]" <?php if ($product_data['content']['state_monthly_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                    Enable monthly purchase
                                </label>

                                <div class="enableItem">
                                    <?=$state_monthly_price;?>
                                </div>

                            </div>

                        </div>

                    </div>

                </li>
            </ul>

        </div>

        <div class="row content-block relative in-flow">

            <div class="columns small-6 relative end" style="z-index:1">
                <h2 class="sub-heading gray-darker">Postcode</h2>
                <fieldset class="un-indent inline">
                    <label for="postcodeEnabled"><input type="checkbox" id="postcodeEnabled" data-target="postcode-accordion" class="accordionEnabler" name="enabled[postcode][filter]" <?php if ($product_data['content']['postcode_filter_enabled'] === 1) {echo 'checked="checked"';} ?>>Enable Postcode Pricing</label>
                </fieldset>
            </div>

            <ul id="postcode-accordion" class="accordion columns small-12 product-accordion" data-accordion data-allow-all-closed="true">
                <li class="accordion-item" data-accordion-item>

                    <a href="#postcode-accordion-content" class="accordion-title" data-target="postcodeEnabled"></a>

                    <div id="postcode-accordion-content" class="accordion-content" data-tab-content>

                        <div class="row">

                            <div id="postcodeTagBox" class="tag-box columns small-6 end">
                                <div class="input-group">
                                    <select class="input-group-field input-field">
                                        <?=$postcode_field_select;?>
                                    </select>
                                    <div class="input-group-button">
                                        <button type="button" class="button btn-post aqua-dark-bg" value="add">+ Add</button>
                                    </div>
                                </div>
                                <input type="text" id="postcode" class="hide value-field"  name="filter[postcode]" value="<?=$postcode_haystack_active;?>" />
                                <div class="tag-list"></div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="columns small-4 enableContainer" aria-expanded="false">
                                <label for="postcodeOnceOffEnabled">
                                    <input type="checkbox" id="postcodeOnceOffEnabled" class="enableBox" name="enabled[postcode][onceoff]" <?php if ($product_data['content']['postcode_onceoff_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                    Enable once off purchase
                                </label>
                                <label for="postcodeOnceOffPrice" class="enableItem">
                                    Once off price
                                    <input type="text" placeholder="$" id="postcodeOnceOffPrice" name="price[postcode][onceoff]" value="<?=sprintf("%01.2f", $product_data['content']['postcode_onceoff_price']); ?>" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                                </label>
                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">
                                <label for="postcodeQuarterlyEnabled">
                                    <input type="checkbox" id="postcodeQuarterlyEnabled" class="enableBox" name="enabled[postcode][quarterly]" <?php if ($product_data['content']['postcode_quarterly_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                    Enable quarterly purchase
                                </label>
                                <label for="postcodeQuarterlyPrice" class="enableItem">
                                    Quarterly price
                                    <input type="text" placeholder="$" id="postcodeQuarterlyPrice" name="price[postcode][quarterly]" value="<?=sprintf("%01.2f", $product_data['content']['postcode_quarterly_price']); ?>" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                                </label>
                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">
                                <label for="postcodeMonthlyEnabled">
                                    <input type="checkbox" id="postcodeMonthlyEnabled" class="enableBox" name="enabled[postcode][monthly]" <?php if ($product_data['content']['postcode_monthly_enabled'] === 1) {echo 'checked="checked"';} ?> />
                                    Enable monthly purchase
                                </label>
                                <label for="postcodeMonthlyPrice" class="enableItem">
                                    Monthly price
                                    <input type="text" placeholder="$" id="postcodeMonthlyPrice" name="price[postcode][monthly]" value="<?=sprintf("%01.2f", $product_data['content']['postcode_monthly_price']); ?>" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                                </label>
                            </div>

                        </div>

                    </div>

                </li>
            </ul>

        </div>

        <div class="row small-collapse">
            <div class="columns small-12">
                <h1 class="gray-darker page-title"><?=PAGE;?> descriptions</h1>
            </div>
        </div>

        <div class="row content-block">
            <div class="columns small-12">

                <label for="shortDescription">
                    Add as <strong>short description</strong> for the products
                    <input type="text" id="shortDescription" class="in-flow" name="short_description" value="<?=$product_data['content']['short_description'];?>" />
                </label>

                <ul id="description-accordion" class="accordion product-description-accordion" data-accordion data-allow-all-closed="true">

                    <li class="accordion-item" data-accordion-item>
                        <a href="#fullDescription" class="accordion-title">Add a <strong>full description</strong> for the product</a>
                        <div id="fullDescription" class="accordion-content" data-tab-content>
                            <textarea id="description" name="description"><?=$product_data['content']['description'];?></textarea>
                        </div>
                    </li>

                    <li class="accordion-item" data-accordion-item>
                        <a href="#sampleData" class="accordion-title">Add a <strong>sample data</strong> for the product</a>
                        <div id="sampleData" class="accordion-content" data-tab-content>
                            <textarea id="sample_data" name="sample_data"><?=$product_data['content']['sample_data'];?></textarea>
                        </div>
                    </li>

                    <li class="accordion-item" data-accordion-item>
                        <a href="#paidTier" class="accordion-title">Add a <strong>paid tier</strong> terms &amp; conditions text</a>
                        <div id="paidTier" class="accordion-content" data-tab-content>
                            <textarea id="paid_tier_terms" name="paid_tier_terms"><?=$product_data['content']['paid_tier_terms'];?></textarea>
                        </div>
                    </li>

                    <li class="accordion-item" data-accordion-item>
                        <a href="#freeTier" class="accordion-title">Add a <strong>free tier</strong> terms &amp; conditions text</a>
                        <div id="freeTier" class="accordion-content" data-tab-content>
                            <textarea id="free_tier_terms" name="free_tier_terms"><?=$product_data['content']['free_tier_terms'];?></textarea>
                        </div>
                    </li>

                </ul>

            </div>
        </div>

        <div class="row submit">
            <div class="columns small-12 medium-6 medium-offset-3 text-center">
                <input type="number" class="hide" name="product_id" value="<?=$product_id;?>" required />
                <button class="button btn-post expanded" type="submit" value="submit">Save Product</button>
            </div>
        </div>

    </form>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
