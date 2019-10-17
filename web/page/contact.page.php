<?php require(HEAD); ?>

    <section class="row title-bar">
        <h2>Contact</h2>
        <h1>descriptive sub h1 text</h1>
    </section>
    <section class="row map-section">
        <div class="google-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3154.172228263215!2d145.30711671584731!3d-37.762559638889215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad6302b6e6cb60f%3A0xeb98042c2431da11!2sE+Ridge+Dr%2C+Chirnside+Park+VIC+3116!5e0!3m2!1sen!2sau!4v1490777484964" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </section>
    <section class="row section-1 bg-white padding-v">
        <div class="columns small-12 medium-6">
            <h3>Company Details</h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
            <p><?= COMPANY_NAME; ?><br />
                <?= ADDRESS; ?>,<br/>
                <?= SUBURB; ?>, <?= STATE; ?>, <?= POSTCODE; ?><br/>
                <?= COUNTRY; ?>
            </p>
            <p>Email: <a href="mailto:<?= CONTACT_EMAIL; ?>?subject=Website Enquiry" title="email us" class="text-link nowrap"><?= CONTACT_EMAIL; ?></a></br>
                Phone: <a href="tel:+613<?= PHONE; ?>" title="call us" class="text-link nowrap">(03)<?= PHONE; ?></a></br>
                <a data-overview="true" data-overview-type="overview3" data-overview-link="<?php echo ROOT; ?>web/overview/index.overview.html?name=map" data-overview-element="false" class="text-link nowrap">View Map</a>
            </p>
        </div>
        <div class="columns small-12 medium-6 contact-form">
            <h3>Enquiry Form</h3>
            <form data-abide noValidate>
                <div class="columns small-12 large-6 padding-left-none">
                    <input type="text" id="firstName" name="form[firstName]" placeholder="First Name" required pattern="text"/>
                </div>
                <div class="columns small-12 large-6 padding-left-none">
                    <input type="text" id="lastname" name="form[surname]" placeholder="Last Name" required pattern="text"/>
                </div>
                <div class="columns small-12 padding-left-none">
                    <input type="text" id="company" name="form[company]" placeholder="Company (if applicable)" pattern="text"/>
                </div>
                <div class="columns small-12 padding-left-none">
                    <input type="email" id="email" name="form[email]" placeholder="Email" required pattern="email"/>
                </div>
                <div class="columns small-12 padding-left-none">
                    <input type="number" id="phone" name="form[phone]" placeholder="Contact Number" pattern="number"/>
                </div>
                <div class="columns small-12 padding-left-none">
                    <textarea type="text" id="message" name="form[message]" placeholder="Message" required pattern="text"></textarea>
                </div>
                <div class="columns small-12 collapse">
                    <input type="submit" id="submit" value="Send" class="button submit right"/>
                    <div class="small-9">
                        <div data-abide-error class="alert callout" style="display: none">
                            <p id="form_validation_error"></p>
                        </div>
                        <div id="form_error" class="alert callout" style="display: none">
                            <p>Your message couldn't be sent<br />Please try again</p>
                        </div>
                        <div id="form_success" class="success callout" style="display: none">
                            <p><i class="fi-success"></i> Thank you for your enquiry</p>
                        </div>
                    </div>
                </div>
                <input name="meta[action]" value="send_email" type="text" class="hide"/>
                <input name="meta[form]" value="contact" type="text" class="hide"/>
                <input name="meta[check]" type="text" class="hide"/>
                <input name="meta[csrf]" type="text" value="<?=$auth->generate_csrf_token('contact');?>" class="hide"/>
            </form>
        </div>

    </section>
    <div id="overview2" class="overview overview2" >
        <a class="overview-close"><i class="icons icon-close"></i></a>
        <div class="overview-inner">
        </div>
    </div>
<?php require(FOOT); ?>
