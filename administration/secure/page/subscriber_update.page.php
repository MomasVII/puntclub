<?php require(HEAD); ?>

<div class="wrapper gray-light-bg">

    <div class="row small-collapse">
        <div class="columns small-12">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
        </div>
    </div>

    <div class="row subscriber">
        <div class="flex-row">

            <?php
                //don't allow calls to action if subscriber isn't paid up
                if($subscriber_data['content']['paid_time'] != 0){
            ?>

                <a id="resend_download_link" class="item button btn-next" href="#" data-open="resend_download_link_modal">Re-send<br />data as link <i class="icons icons-arrow-2 xxsmall white"></i></a>

                <?php
                    if($subscribed_product['content']['disable_attachment_delivery'] === 1){
                        echo '<a id="resend_data_file" class="item button btn-next disabled" href="#" data-open="resend_data_file_modal">Re-send<br />as file disabled <i class="icons icons-arrow-2 xxsmall white"></i></a>';
                    }else{
                        echo '<a id="resend_data_file" class="item button btn-next" href="#" data-open="resend_data_file_modal">Re-send<br />data as file <i class="icons icons-arrow-2 xxsmall white"></i></a>';
                    }
                ?>

                <a id="resend_invoice" class="item button btn-next" href="#" data-open="resend_invoice_modal">Re-send<br />invoice <i class="icons icons-arrow-2 xxsmall white"></i></a>

                <a id="download_data" class="item button btn-next" href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id'] . '&download_data=1'; ?>">Download<br />data as file <i class="icons icons-arrow-2 xxsmall white"></i></a>

                <a id="download_invoice" class="item button btn-next" href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id'] . '&download_invoice=1'; ?>" download>Download<br />invoice <i class="icons icons-arrow-2 xxsmall white"></i></a>

                <?php
                    if($subscriber_data['content']['manually_terminated'] > 0){
                        echo '<a id="terminate_btn" class="item button btn-next disabled" href="#">Already<br />terminated <i class="icons icons-arrow-2 xxsmall white"></i></a>';
                    }else{
                        echo '<a id="terminate_btn" class="item button btn-next" href="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id'] . '&terminate=1">Terminate<br />subscriber <i class="icons icons-arrow-2 xxsmall white"></i></a>';
                    }
                ?>
            <?php } ?>
        </div>
    </div>

    <?php
        //if subscriber is a pending eft transaction
        if($subscriber_data['content']['paid_time'] === 0 && $subscriber_data['content']['paid_method'] === 'eft' && $subscriber_data['content']['manually_terminated'] === 0){
    ?>
    	<div class="row">
            <div class="warning callout white">
                <p class="no_mb">
                    <span class="icons icons-alert xsmall">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </span>
                    This subscriber has a purchase request requiring review
                    <div class="warning_cta">
                        <a href="#" data-open="confirm_eft_modal" class="button btn_warning">Approve</a>
                        <a href="#" data-open="confirm_deny_eft_modal" class="button btn_warning">Deny</a>
                    </div>
                </p>
            </div>
    	</div>
    <?php } ?>

    <form data-abide novalidate role="form" action="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id']; ?>" method="post"  enctype="multipart/form-data" id="update">

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

                <label for="businessName">Business name *
                    <input type="text" id="businessName" name="business_name" value="<?=$subscriber_data['content']['business_name'];?>" required />
                </label>

                <label for="businessAddress">Business address *
                    <input type="text" id="businessAddress" name="business_address" value="<?=$subscriber_data['content']['business_address'];?>" required />
                </label>

                <label for="businessSuburb">Business suburb
                    <input type="text" id="businessSuburb" name="business_suburb" value="<?=$subscriber_data['content']['business_suburb'];?>" required />
                </label>

                <label for="businessState">Business state *
                    <select id="businessState" name="business_state" required>
                        <?=$state_print;?>
                    </select>
                </label>

                <label for="businessPostcode">Business postcode *
                    <input type="text" id="businessPostcode" name="business_postcode" value="<?=$subscriber_data['content']['business_postcode'];?>" required pattern="^[0-9]{4}$" />
                </label>

                <label for="businessDepartment">Business department *
                    <input type="text" id="businessDepartment" name="business_department" value="<?=$subscriber_data['content']['business_department'];?>" required />
                </label>

            </div>
            <div class="columns small-12 medium-6">

                <label for="contactName">Contact name *
                    <input type="text" id="contactName" name="contact_name" value="<?=$subscriber_data['content']['contact_name'];?>" required />
                </label>

                <label for="contactPhone">Phone number *
                    <input type="text" id="contactPhone" name="contact_phone" value="<?=$subscriber_data['content']['contact_phone'];?>" required pattern="^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$" />
                </label>

                <label for="deliveryEmail">Delivery email address *
                    <input type="email" id="deliveryEmail" name="delivery_email" value="<?=$subscriber_data['content']['delivery_email'];?>" autocomplete="off" required pattern="email" />
                </label>

                <label for="supportEmail">Support email address
                    <input type="email"  id="supportEmail" name="support_email" value="<?=$subscriber_data['content']['support_email'];?>" autocomplete="off" pattern="email" />
                </label>

                <label for="businessAbn">ABN / ACN
                    <input type="text"  id="businessAbn" name="business_abn" value="<?=$subscriber_data['content']['business_abn'];?>" />
                </label>

                <?php if($subscribed_product['content']['disable_attachment_delivery'] === 0){ ?>
                    <fieldset class="un-indent">
                        <label for="attachPayload">
                            <input type="checkbox" id="attachPayload" name="attach_payload" <?php if ($subscriber_data['content']['attach_payload'] === 1) {echo 'checked="checked"';} ?>>
                            Deliver data as attachment
                        </label>
                    </fieldset>
                <?php } ?>
            </div>

			<div class="columns small-12">
				<fieldset class="un-indent">
					<input type="checkbox" id="optInMarketing" name="marketing_opt_in" <?php if ($subscriber_data['content']['marketing_opt_in'] === 1) {echo 'checked="checked"';} ?>>
					<label for="optInMarketing">Subscriber wishes to receive information about other products and services from Australia Post</label>
				</fieldset>
			</div>
            <div class="columns small-12">
				<fieldset class="un-indent">
                    <label for="data_partner">
                        <input type="checkbox" name="reseller" id="data_partner" <?php if ($subscriber_data['content']['reseller'] === 1) {echo 'checked="checked"';} ?>>
                        This subscriber is a Data Partner or Reseller
                    </label>
                </fieldset>
            </div>
        </div>

        <div class="row edit-subscriber">

            <div class="flex-row">

                <div class="item content-block">
                    <h2 class="sub-heading">Subscription Summary</h2>

                    <table>
                        <tbody class="two-col">
                            <tr>
                                <td>Subscription status:</td>
                                <td><?= $status_print; ?></td>
                            </tr>
                            <tr>
                                <td>Product:</td>
                                <td><?= $product_name_print; ?> (<?= $product_sku_print; ?>)</td>
                            </tr>
                            <tr>
                                <td>Term of use:</td>
                                <td><?= $product_term; ?></td>
                            </tr>
                            <tr>
                                <td>Update frequency:</td>
                                <td><?=$delivery_interval; ?></td>
                            </tr>
                            <tr>
                                <td>Coverage:</td>
                                <td><?=$data_filter; ?></td>
                            </tr>
                            <tr>
                                <td>Selected:</td>
                                <td><?=$data_needle; ?></td>
                            </tr>
                            <tr>
                                <td>Start date:</td>
                                <td><?=$start_date; ?></td>
                            </tr>
                            <tr>
                                <td>Paid date:</td>
                                <td><?=$paid_date; ?></td>
                            </tr>
                            <tr>
                                <td>Expiry date:</td>
                                <td><?=$expiry_date; ?></td>
                            </tr>
                            <tr>
                                <td>Total downloads:</td>
                                <td><?=$subscriber_data['content']['download_count']; ?></td>
                            </tr>
                            <tr>
                                <td>Last delivery:</td>
                                <td><input type="text" id="lastDelivery" name="last_delivery_time" value="<?= $last_delivery; ?>" /></td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 class="sub-heading">Payment Details</h2>

                    <table>
                        <tbody class="two-col">
                            <tr>
                                <td>Payment type:</td>
                                <td><?=$paid_method; ?></td>
                            </tr>
                            <tr>
                                <td>Transaction total:</td>
                                <td><?= sprintf("%01.2f", $subscriber_data['content']['paid_total']); ?></td>
                            </tr>
                            <tr>
                                <td>Payment reference:</td>
                                <td><input type="text" id="paidReference" name="paid_reference" value="<?=$paid_reference; ?>" <?=$edit_paid_reference;?> /></td>
                            </tr>
                            <tr>
                                <td>Invoice number:</td>
                                <td><?=$subscriber_data['content']['subscriber_id']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="item content-block">
                    <h2 class="sub-heading">Download History</h2>
                    <?= $download_history_print; ?>
                </div>
            </div>
        </div>

        <div class="row submit">
            <div class="columns small-12 medium-6 medium-offset-3 text-center">
                <input type="hidden" class="input-group-field input-field" name="action" value="update">
                <button class="button btn-post expanded" type="submit" value="submit">Save Subscriber details</button>
            </div>
        </div>

    </form>

    <!-- EFT Approve  EFT confirm modal -->
	<div class="reveal tiny text-center" id="confirm_eft_modal" aria-labelledby="resend_invoice_modal_header" data-reveal>
        <h2 id="resend_invoice_modal_header" class="sub-heading">Are you sure you want to<br />approve this request?</h2>

        <a id="confirm" href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id'] . '&eft_confirm=allow'; ?>" class="button btn_dialogue confirm">Yes</a>
        <button data-close aria-label="Close Accessible Modal" class="button btn_dialogue" id="cancel">No</button>

        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

	<!-- EFT Deny  EFT confirm modal -->
	<div class="reveal tiny text-center" id="confirm_deny_eft_modal" aria-labelledby="resend_invoice_modal_header" data-reveal>
        <h2 id="resend_invoice_modal_header" class="sub-heading">Are you sure you want to<br />deny this request?</h2>

        <a id="confirm" href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id'] . '&terminate=1'; ?>" class="button btn_dialogue confirm">Yes</a>
        <button data-close aria-label="Close Accessible Modal" class="button btn_dialogue" id="cancel">No</button>

        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Resend invoice modal  -->
    <div class="reveal" id="resend_invoice_modal" aria-labelledby="resend_invoice_modal_header" data-reveal>
        <h2 id="resend_invoice_modal_header" class="sub-heading">Re send Invoice</h2>
        <form data-abide novalidate action="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id'];?>" method="post" enctype="multipart/form-data" id="resend__data_file_form">
            <label>Please enter the recipient email</label>
            <div class="input-group">
                <input type="text" class="input-group-field input-field" name="email"  value="<?=$subscriber_data['content']['delivery_email'];?>" autocomplete="off" required pattern="email">
                <input type="hidden" class="input-group-field input-field" name="action" value="resend_invoice">

                <div class="input-group-button">
                    <button type="submit" class="button btn-post gap" id="save">Send</button>
                </div>
            </div>
        </form>

        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Resend data file modal -->
    <div class="reveal" id="resend_data_file_modal" aria-labelledby="resend_data_file_modal_header" data-reveal>
        <h2 id="resend_data_file_modal_header" class="sub-heading">Re send data file</h2>

        <form data-abide novalidate action="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id']; ?>" method="post" enctype="multipart/form-data" id="resend__data_file_form">
            <label>Please enter the recipient email</label>
            <div class="input-group">
                <input type="text" class="input-group-field input-field" placeholder="Recipients Email" name="email" value="<?=$subscriber_data['content']['delivery_email'];?>" autocomplete="off" required pattern="email">
                <input type="hidden" class="input-group-field input-field" name="action" value="resend_attached">
                <div class="input-group-button">
                    <button type="submit" class="button btn-post gap" id="save">Send</button>
                </div>
            </div>
        </form>

        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


    <!-- Resend download link modal -->
    <div class="reveal" id="resend_download_link_modal" aria-labelledby="resend_download_link_modal_header" data-reveal>
        <h2 id="resend_download_link_modal_header" class="sub-heading">Re send download link</h2>

        <form data-abide novalidate action="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']) . '?id=' . $subscriber_data['content']['subscriber_id']; ?>" method="post" enctype="multipart/form-data" id="resend__data_file_form">
            <label>Please enter the recipient email</label>
            <div class="input-group">
                <input type="text" class="input-group-field input-field" placeholder="Recipients Email" name="email" value="<?=$subscriber_data['content']['delivery_email'];?>" autocomplete="off" required pattern="email">
                <input type="hidden" class="input-group-field input-field" name="action" value="resend_link">
                <div class="input-group-button">
                    <button type="submit" class="button btn-post gap" id="save">Send</button>
                </div>
            </div>
        </form>

        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
