<form data-abide noValidate>
    <div class="columns small-12 large-6">
        <input type="text" id="firstName" name="form[firstName]" placeholder="First Name" required pattern="text"/>
    </div>
    <div class="columns small-12 large-6">
        <input type="text" id="lastname" name="form[surname]" placeholder="Last Name" required pattern="text"/>
    </div>
    <div class="columns small-12">
        <input type="text" id="company" name="form[company]" placeholder="Company (if applicable)" pattern="text"/>
    </div>
    <div class="columns small-12">
        <input type="email" id="email" name="form[email]" placeholder="Email" required pattern="email"/>
    </div>
    <div class="columns small-12">
        <input type="number" id="phone" name="form[phone]" placeholder="Contact Number" pattern="number"/>
    </div>
    <div class="columns small-12">
        <textarea type="text" id="message" name="form[message]" placeholder="Message" required pattern="text"></textarea>
    </div>
    <div class="columns small-12 collapse">
        <input type="submit" id="submit" value="Send" class="button submit right"/>
        <div class="small-9">
            <div data-abide-error class="alert callout" style="display: none">
                <p id="form_validation_error">Error</p>
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
    <?php /*<input name="meta[csrf]" type="text" value="<?=$auth->generate_csrf_token('contact');?>" class="hide"/> */ ?>
</form>