
  <div class="row">
    <div class="columns small-12 text-left">
      <form data-abide noValidate action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>" method="post">

        <div class="columns small-12">

          <label> Name </label>
          <input type="text" id="name" name="name" placeholder="" required pattern="text"/>

          <label> Email</label>
          <input type="email" id="email" name="email" placeholder="" required pattern="email"/>

          <label> Phone</label>
          <input type="number" id="phone" name="phone" placeholder="" pattern="number"/>

          <label> Message</label>
          <textarea type="text" rows="3" id="message" name="message" placeholder="" required pattern="text"></textarea>

          <div class="g-recaptcha" data-sitekey="6LcvW5IUAAAAAHyOX5tSRR1cnAf5eJLk8z4n4v4Z"></div>
        </div>

        <div class="columns small-12 medium-9 large-10">
            <div data-abide-error class="alert callout" style="display: none">
                <p id="form_validation_error">Please complete the required fields</p>
            </div>
            <?=$response;?>
        </div>
        <div class="columns small-12 medium-3 large-2" style="float: right">
            <button type="submit" id="submit" class="button submit right">Send</button>
        </div>

        <input name="meta[check]" type="text" class="hide"/>
      </form>

    </div>
  </div>
