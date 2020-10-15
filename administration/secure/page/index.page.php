<?php require(HEAD); ?>
<div class="wrapper aqua-light-bg">

    <div class="row expanded">

        <div class="row">

            <div id="login-panel" class="columns small-12 medium-6 medium-offset-3">

                <h1 class="text-center">Admin login details</h1>


                <form data-abide novalidate id="login-form" class="form" action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>"  method="post" enctype="multipart/form-data">


					<div id="response" data-abide-error class="alert callout" style="display: none;">
	                    <p><i class="fi-alert"></i> <?=$response;?> </p>
	                </div>

                    <label for="username">Username&nbsp;<span class="symbol-req">*</span>
                        <input id="username" maxlength="200" name="username" tabindex="1" type="text" value="" autocomplete="off" required/>
                    </label>

                    <label for="password">Password&nbsp;<span class="symbol-req">*</span>
                        <input id="password" maxlength="200" name="password" tabindex="2" type="password" value="" autocomplete="off" required/>
                    </label>

                    <label for="check" class="hide">Check&nbsp;<span class="symbol-req">*</span>
                        <input id="check" maxlength="60" name="check" type="text" value=""/>
                    </label>

                    <label for="csrf_name" class="hide">Name&nbsp;<span class="symbol-req">*</span>
                        <input id="csrf_name" name="csrf_name" type="text" value="login"/>
                    </label>

                    <label for="csrf_token" class="hide">Token&nbsp;<span class="symbol-req">*</span>
                        <input id="csrf_token" name="csrf_token" type="text" value="<?=$auth->generate_csrf_token('login');?>"/>
                    </label>

                    <button class="button btn-post btn-login float-right" type="submit" value="Login">Login</button>

                </form>

            </div>
        </div>

        <div class="row expanded login-image-section">
            <img src="<?=LOCAL;?>web/image/admin_home.png" alt="Movers Statistics truck"/>
        </div>

    </div>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
