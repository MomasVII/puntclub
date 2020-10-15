
<?php require(HEAD); ?>
<div class="wrapper gray-light-bg">

    <form data-abide novalidate role="form" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data" id="insert_step_one" name="insert_step_one" <?php if($step != 1){echo ' style="display: none;"';}?>>

		<div id="waitSpinner" class="spinner-wrapper">
			<div class="spinner-mask">
				<div class="spinner-container">
					<div class="spinner"></div>
				</div>
			</div>
		</div>

        <div class="row small-collapse">
            <div class="columns small-6">
                <h1 class="gray-darker page-title"><?=PAGE;?> - Step 1</h1>
            </div>
            <div class="columns small-6">
                <a href="<?=LOCAL;?>product.html" class="button page-title btn-gray float-right">Return to Product List</a>
            </div>
        </div>

        <div class="row content-block">

            <div class="columns small-12">
                <p>
                    <small>* Required fields</small>
                </p>
            </div>

			<div class="columns small-12">
	            <div id="response" data-abide-error class="alert callout" style="display: none;">
	                <p><i class="fi-alert"></i> <?=$response;?> </p>
	            </div>
	        </div>

            <div class="columns small-12 medium-6">

                <label for="productName">Product name&nbsp;*
                    <input id="productName" type="text" name="name" required/>
                </label>

                <label for="SKU">SKU&nbsp;*
                    <input id="SKU" type="text" name="sku" required pattern="alpha_numeric" />
                </label>

            </div>
            <div class="columns small-12 medium-6">

                <label for="subscriptionTerms">Subscription term (months)&nbsp;*
                    <input id="subscriptionTerms" type="text" name="subscription_term" required pattern="number"  />
                </label>

            </div>

            <div class="columns small-12"></div>

            <div class="columns small-12 medium-6">

                <fieldset class="file-upload-2">
                    <label>Upload header image&nbsp;*</label>
                    <input type="file" name="header_image" id="headerFile" class="inputfile" required />
                    <label for="headerFile">
                        <span>Please choose a 1080px x 314px PNG image</span>
                        <strong>Browse</strong>
                    </label>
                </fieldset>

            </div>
            <div class="columns small-12 medium-6">

                <fieldset class="file-upload-2">
                    <label>Upload thumbnail image&nbsp;*</label>
                    <input type="file" name="thumbnail_image" id="thumbnailFile" class="inputfile" required />
                    <label for="thumbnailFile">
                        <span>Please choose a 150px x 150px PNG image</span>
                        <strong>Browse</strong>
                    </label>
                </fieldset>

            </div>

            <div class="columns small-12">
                <h2 class="sub-heading gray-darker">Add Paid Tier product data&nbsp;*</h2>
            </div>

            <div class="columns small-12 medium-6 end">

                <fieldset class="file-upload-2">

                    <label class="main-label">Upload product data</label>
                    <input type="file" name="paid_tier_file" id="paidFile" class="inputfile" required />
                    <label for="paidFile">
                        <span>Please choose a CSV spreadsheet</span>
                        <strong>Browse</strong>
                    </label>

                </fieldset>

            </div>
            <div class="columns small-12">
                <fieldset class="un-indent">
                    <input id="published" name="published" type="checkbox" ><label for="published">Publish Product</label>
                    <input id="enable_free_tier" name="enable_free_tier" type="checkbox"><label for="enable_free_tier">Enable Free Tier</label>
                    <input id="enforce_delta_delivery" name="enforce_delta_delivery" type="checkbox"><label for="enforce_delta_delivery">Deliver product as delta</label>
                    <input id="disable_attachment_delivery" name="disable_attachment_delivery" type="checkbox"><label for="disable_attachment_delivery">Disable attachment delivery</label>
                </fieldset>
            </div>

            <div class="free_tier">

                <div class="columns small-12">
                    <h2 class="sub-heading gray-darker">Add Free Tier product file</h2>
                </div>

                <div class="columns small-12 medium-6 end">
                    <fieldset class="file-upload-2">
                        <label>Upload free file</label>
                        <input type="file" name="free_tier_file" id="freeFile" class="inputfile" />
                        <label for="freeFile">
                            <span>Please choose a PDF document</span>
                            <strong>Browse</strong>
                        </label>
                    </fieldset>
                </div>

            </div>

            <div class="columns small-12">
                <button class="button btn-post" type="submit" value="step_one" name="action" id="step_one">Save details</button>
            </div>

        </div>

    </form>

    <form data-abide novalidate role="form" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data" id="insert_step_two" name="insert_step_two" <?php if($step != 2){echo ' style="display: none;"';}?>>

        <div id="waitSpinner" class="spinner-wrapper">
			<div class="spinner-mask">
				<div class="spinner-container">
					<div class="spinner"></div>
				</div>
			</div>
		</div>

        <div class="row small-collapse">
            <div class="columns small-12">
                <h1 class="gray-darker page-title"><?=PAGE;?> pricing - Step 2</h1>
            </div>
        </div>

        <div class="row content-block relative">

            <div class="columns small-12">
                <div id="response" data-abide-error class="alert callout" style="display: none;">
                    <p><i class="fi-alert"></i> <?=$response;?> </p>
                </div>
            </div>

            <div class="columns small-6 relative end" style="z-index:1">
                <h2 class="sub-heading gray-darker">National</h2>
                <fieldset class="un-indent inline">
                    <label for="nationalEnabled">
                        <input type="checkbox" id="nationalEnabled" name="enabled[national][filter]" data-target="national-accordion" class="accordionEnabler">
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
                                <input type="checkbox" id="nationalOnceOffEnabled" name="enabled[national][onceoff]" class="enableBox" />
                                Enable once off purchase
                            </label>
                            <label for="nationalOnceOffPrice" class="enableItem">
                                Once off price
                                <input type="text" placeholder="$" id="nationalOnceOffPrice" name="price[national][onceoff]" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                            </label>
                        </div>
                        <div class="columns small-4 enableContainer" aria-expanded="false">
                            <label for="nationalQuarterlyEnabled">
                                <input type="checkbox" id="nationalQuarterlyEnabled" name="enabled[national][quarterly]" class="enableBox" />
                                Enable quarterly purchase
                            </label>
                            <label for="nationalQuarterlyPrice" class="enableItem">
                                Quarterly price
                                <input type="text" placeholder="$" id="nationalQuarterlyPrice" name="price[national][quarterly]" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                            </label>
                        </div>
                        <div class="columns small-4 end enableContainer" aria-expanded="false">
                            <label for="nationalMonthlyEnabled">
                                <input type="checkbox" id="nationalMonthlyEnabled" name="enabled[national][monthly]" class="enableBox" />
                                Enable monthly purchase
                            </label>
                            <label for="nationalMonthlyPrice" class="enableItem">
                                Monthly price
                                <input type="text" placeholder="$" id="nationalMonthlyPrice" name="price[national][monthly]" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
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
                    <label for="stateEnabled"><input type="checkbox" id="stateEnabled" name="enabled[state][filter]" data-target="state-accordion" class="accordionEnabler">Enable State Pricing</label>
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
                                <input type="text" name="filter[state]" id="state_filter" class="hide value-field" />
                                <div class="tag-list"></div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="columns small-4 enableContainer" aria-expanded="false">

                                <label for="stateOnceOffEnabled">
                                    <input type="checkbox" id="stateOnceOffEnabled" name="enabled[state][onceoff]" class="enableBox" />
                                    Enable once off purchase
                                </label>

                                <div class="enableItem">
                                    <?=$state_onceoff_price;?>
                                </div>
                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">

                                <label for="stateQuarterlyEnabled">
                                    <input type="checkbox" id="stateQuarterlyEnabled" name="enabled[state][quarterly]" class="enableBox" />
                                    Enable quarterly purchase
                                </label>

                                <div class="enableItem">
                                    <?=$state_quarterly_price;?>
                                </div>

                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">

                                <label for="stateMonthlyEnabled">
                                    <input type="checkbox" id="stateMonthlyEnabled" name="enabled[state][monthly]" class="enableBox" />
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
                    <label for="postcodeEnabled"><input type="checkbox" id="postcodeEnabled" name="enabled[postcode][filter]" data-target="postcode-accordion" class="accordionEnabler">Enable Postcode Pricing</label>
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
                                <input type="text" name="filter[postcode]" id="postcode_filter" class="hide value-field" />
                                <div class="tag-list"></div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="columns small-4 enableContainer" aria-expanded="false">
                                <label for="postcodeOnceOffEnabled">
                                    <input type="checkbox" id="postcodeOnceOffEnabled" name="enabled[postcode][onceoff]" class="enableBox" />
                                    Enable once off purchase
                                </label>
                                <label for="postcodeOnceOffPrice" class="enableItem">
                                    Once off price
                                    <input type="text" placeholder="$" id="postcodeOnceOffPrice" name="price[postcode][onceoff]" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                                </label>
                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">
                                <label for="postcodeQuarterlyEnabled">
                                    <input type="checkbox" id="postcodeQuarterlyEnabled" name="enabled[postcode][quarterly]" class="enableBox" />
                                    Enable quarterly purchase
                                </label>
                                <label for="postcodeQuarterlyPrice" class="enableItem">
                                    Quarterly price
                                    <input type="text" placeholder="$" id="postcodeQuarterlyPrice" name="price[postcode][quarterly]" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                                </label>
                            </div>
                            <div class="columns small-4 enableContainer" aria-expanded="false">
                                <label for="postcodeMonthlyEnabled">
                                    <input type="checkbox" id="postcodeMonthlyEnabled" name="enabled[postcode][monthly]" class="enableBox" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
                                    Enable monthly purchase
                                </label>
                                <label for="postcodeMonthlyPrice" class="enableItem">
                                    Monthly price
                                    <input type="text" placeholder="$" id="postcodeMonthlyPrice" name="price[postcode][monthly]" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" />
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
                    Add a <strong>short description</strong> for the product
                    <input type="text" id="productBlurb" name="short_description" class="in-flow"/>
                </label>

                <ul id="description-accordion" class="accordion product-description-accordion" data-accordion data-allow-all-closed="true">

                    <li class="accordion-item" data-accordion-item>
                        <a href="#fullDescription" class="accordion-title">Add a <strong>full description</strong> for the product</a>
                        <div id="fullDescription" class="accordion-content" data-tab-content>
                            <textarea id="description" name="description"></textarea>
                        </div>
                    </li>

                    <li class="accordion-item" data-accordion-item>
                        <a href="#sampleData" class="accordion-title">Add a <strong>sample data</strong> for the product</a>
                        <div id="sampleData" class="accordion-content" data-tab-content>
                            <textarea id="sample_data" name="sample_data"></textarea>
                        </div>
                    </li>

                    <li class="accordion-item" data-accordion-item>
                        <a href="#paidTier" class="accordion-title">Add a <strong>paid tier</strong> terms &amp; conditions text</a>
                        <div id="paidTier" class="accordion-content" data-tab-content>
                            <textarea id="paid_tier_terms" name="paid_tier_terms"></textarea>
                        </div>
                    </li>

                    <li class="accordion-item" data-accordion-item>
                        <a href="#freeTier" class="accordion-title">Add a <strong>free tier</strong> terms &amp; conditions text</a>
                        <div id="freeTier" class="accordion-content" data-tab-content>
                            <textarea id="free_tier_terms" name="free_tier_terms"></textarea>
                        </div>
                    </li>

                </ul>

            </div>
        </div>

        <div class="row submit">
            <div class="columns small-12 medium-6 medium-offset-3 text-center">

                <input type="number" class="hide" name="product_id" value="<?=$product_id;?>" required />
                <button class="button btn-post expanded" type="submit" value="step_two" name="action" id="step_two">Save Product</button>

            </div>
        </div>

    </form>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
