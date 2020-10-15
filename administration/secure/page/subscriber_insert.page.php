<?php require(HEAD); ?>

<div class="wrapper gray-light-bg subscriber">

    <div class="row small-collapse">
        <div class="columns small-12">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
        </div>
    </div>

    <form data-abide novalidate class="form-horizontal" role="form" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" id="insert">

		<div id="waitSpinner" class="spinner-wrapper">
			<div class="spinner-mask">
				<div class="spinner-container">
					<div class="spinner"></div>
				</div>
			</div>
		</div>

        <div class="row content-block">

            <div class="columns small-12">
                <h2 class="sub-heading">Add Contact Details <small>* Required fields</small></h2>
            </div>

            <div class="columns small-12">
                <div id="response" data-abide-error class="alert callout" style="display: none;">
                    <p><i class="fi-alert"></i> <?=$response;?> </p>
                </div>
            </div>

            <div class="columns small-12 medium-6">

                <label for="businessName">Business name&nbsp;*
                    <input type="text" id="businessName" name="business_name" required />
                </label>

                <label for="businessAddress">Business address&nbsp;*
                    <input type="text" id="businessAddress" name="business_address" required />
                </label>

                <label for="businessSuburb">Business suburb&nbsp;*
                    <input type="text" id="businessSuburb" name="business_suburb" required />
                </label>

                <label for="businessState">Business state&nbsp;*
                    <select id="businessState" name="business_state" required >
						<?=$state_print;?>
					</select>
                </label>

                <label for="businessState">Business postcode&nbsp;*
                    <input type="text" id="businessPostcode" name="business_postcode" required pattern="^[0-9]{4}$" />
                </label>

                <label for="businessState">Business department&nbsp;*
                    <input type="text" id="businessDepartment" name="business_department" required />
                </label>

            </div>
            <div class="columns small-12 medium-6">

                <label for="contactName">Contact name&nbsp;*
                    <input type="text" id="contactName" name="contact_name" required />
                </label>

                <label for="contactPhone">Phone number&nbsp;*
                    <input type="text" id="contactPhone" name="contact_phone" required pattern="^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$" />
                </label>

                <label for="deliveryEmail">Delivery email address&nbsp;*
                    <input type="email" id="deliveryEmail" name="delivery_email" autocomplete="off" required pattern="email" />
                </label>

                <label for="supportEmail">Support email address
                    <input type="email"  id="supportEmail" name="support_email" autocomplete="off" pattern="email" />
                </label>

                <label for="abnacn">ABN / ACN
                    <input type="text"  id="abnacn" name="business_abn" />
                </label>

            </div>

			<div class="columns small-12">
				<fieldset class="un-indent">
					<input type="checkbox" id="optInMarketing" name="marketing_opt_in" checked />
					<label for="optInMarketing">Subscriber wishes to receive information about other products and services from Australia Post</label>
				</fieldset>
			</div>

        </div>

        <div class="row content-block">

            <div class="columns small-12">
                <h2 class="sub-heading">Add Product Details</h2>
            </div>
            <div class="clear-fix">
                <div class="columns small-12 medium-4">
                    <label for="product">Product&nbsp;*</label>
                    <select id="product" name="product" required>
                        <?=$product_select_print;?>
                    </select>
                </div>

                <div class="columns small-12 medium-3">
                    <label for="start_time">Paid date&nbsp;*
                        <input type="text" id="start_time" name="paid_time" required/>
                    </label>
                </div>

                <div class="columns small-12 medium-3 end">
                    <label for="end_time">Expiry date&nbsp;*
                        <input type="text" id="end_time" name="expiry_time" required/>
                    </label>
                </div>
            </div>

			<div id="byNational" style="display: none;" class="pricing-block">
	            <div class="columns small-12">
	                <h3 class="sub-heading">National</h3>
	                <span>Please select a subscription option</span>
	            </div>

	            <fieldset class="radio-group columns small-12"></fieldset>
			</div>

            <div id="byState" style="display: none;" class="pricing-block">
                <div class="columns small-12">
                    <h3 class="sub-heading">By State</h3>
                    <span>Please select a state/s and a subscription option</span>
                </div>

                <div class="columns small-12">
                    <div class="vertical-checkbox-group"></div>
                </div>

                <fieldset class="radio-group columns small-12"></fieldset>
            </div>

            <div id="byPostcode" style="display: none;" pricing-block>
                <div class="columns small-12">
                    <h3 class="sub-heading">By Postcode</h3>
                    <span>Please add your preferred postcode/s and a subscription option</span>
                </div>

                <div id="postcodeTagBox" class="tag-box columns small-12 medium-6">
                    <div class="input-group">
                        <input type="text" class="input-group-field input-field" placeholder="Enter Postcode">
                        <div class="input-group-button">
                            <button type="button" class="button red" value="add">+ Add</button>
                        </div>
                    </div>
                    <input type="text" id="postcode" class="hide value-field" value=""/>
                    <div class="tag-list"></div>
                </div>

                <fieldset class="radio-group columns small-12"></fieldset>
            </div>

        </div>

        <div class="row content-block">

            <div class="columns small-12">
                <h2 class="sub-heading">Add Payment Details</h2>
            </div>

            <div class="inner-row">
                <div class="columns small-12 medium-4">
                    <label for="payment_type">Payment type &nbsp;*</label>
                    <select name="paid_method" id="payment_type" required>
                        <option value="" selected>Please select</option>
                        <option value="credit">SecurePay (credit)</option>
                        <option value="eft">EFT (funds transfer)</option>
                    </select>
                </div>
            </div>

            <div class="inner-row">
                <div class="columns small-12 medium-3">
                    <label for="total">Transaction total ($)</label>
                    <input type="text" name="paid_total" id="total" />
                </div>
                <div class="columns small-12 medium-3 end">
                    <label for="reference">Payment reference</label>
                    <input type="text" name="paid_reference" id="reference" />
                </div>
            </div>

            <div class="inner-row">
                <div class="columns small-12">
                    <label for="data_partner">
                        <input type="checkbox" name="reseller" id="data_partner"/>
                        This subscriber is a Data Partner or Reseller
                    </label>
                </div>
            </div>
        </div>

        <div class="row submit">
            <div class="columns small-12 medium-6 medium-offset-3 text-center">

                <input type="text" id="filter" name="filter" class="hide" />
                <input type="text" id="needle" name="needle" class="hide" />
                <input type="text" id="interval" name="interval" class="hide" />

                <button class="button btn-post expanded" type="submit" value="submit" data-disabled="true">Save Subscriber details</button>
            </div>
        </div>

    </form>

</div>
<!-- END wrapper -->

<?php require(FOOT); ?>
