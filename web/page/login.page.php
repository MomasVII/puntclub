<?php require(HEAD); ?>

<div class="container-fluid home_container signup_container">
    <div class="row">
        <div class="offset-lg-7 col-lg-5">
            <div class="login_container">
                <a href="/"><h4>Home</h4></a>
                <!---Reusable button--->
                <div class="gradient_button_2">
                    <a href="/signup">
                        <div class="button_content">
                            <i class="fas fa-user"></i>
                            <div class="vertical_line"></div>
                            <h5>Login</h5>
                        </div>
                    </a>
                </div>
                <!---Button end--->
            </div>
        </div>
    </div>
    <div class="punt_club_banner">
        <h1>LOG.IN</h1>
        <p>Your Punt.Club Journey Starts Here</p>
    </div>
    <div class="empty">
    </div>
</div>
<div class="container-fluid signup_form">
    <div class="row">
        <div class="col-md-6 offset-md-3">

            <form data-abide novalidate id="login-form" action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>" method="post" enctype="multipart/form-data">

                <div id="response" data-abide-error class="alert callout" style="display: none;">
                    <p><i class="fi-alert"></i> <?=$response;?> </p>
                </div>

                <label class="form_label"><i class="fas fa-user"></i>
                    <input type="text" class="form_text" placeholder="Full Name" id="username" maxlength="200" name="username" tabindex="1" autocomplete="off" required />
                </label>
                <label class="form_label"><i class="fas fa-envelope"></i>
                    <input id="password" class="form_text" maxlength="200" name="password" tabindex="2" type="password" autocomplete="off" required placeholder="Email"  />
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

                <div class="sign_up_buttons">
                    <div class="gradient_button">
                        <a href="#sign-up">
                            <div class="button_content">
                                <i class="fas fa-user"></i>
                                <div class="vertical_line"></div>
                                <h5>Login</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
