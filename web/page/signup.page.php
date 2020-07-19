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
        <h1>SIGN.UP</h1>
        <p>Your Punt.Club Journey Starts Here</p>
    </div>
    <div class="empty">
    </div>
</div>
<div class="container-fluid signup_form">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form>
                <label class="form_label"><i class="fas fa-user"></i>
                    <input type="text" class="form_text" name="name" placeholder="Full Name" />
                </label>
                <label class="form_label"><i class="fas fa-envelope"></i>
                    <input type="text" class="form_text" name="email" placeholder="Email" />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="password" class="form_text" name="password" placeholder="Password" />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="password" class="form_text" name="confirm_password" placeholder="Confirm Password" />
                </label>
                <div class="sign_up_buttons">
                    <div class="gradient_button">
                        <a href="#sign-up">
                            <div class="button_content">
                                <i class="fas fa-user"></i>
                                <div class="vertical_line"></div>
                                <h5>Sign Up</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
