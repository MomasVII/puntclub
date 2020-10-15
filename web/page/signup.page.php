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
            <form data-abide novalidate role="form" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data" id="insert">

    			<div id="waitSpinner" class="spinner-wrapper">
    				<div class="spinner-mask">
    					<div class="spinner-container">
    						<div class="spinner"></div>
    					</div>
    				</div>
    			</div>

                <div class="columns small-12">
                    <div id="response" data-abide-error class="alert callout" style="display: none;">
                        <p><i class="fi-alert"></i> <?=$response;?> </p>
                    </div>
                </div>


                <label class="form_label"><i class="fas fa-user"></i>
                    <input id="firstName" class="form_text" tabindex="1" type="text" name="first_name" placeholder="First Name" required/>
                </label>

                <label class="form_label"><i class="fas fa-user"></i>
                    <input id="lastName" class="form_text" tabindex="2" type="text" name="last_name" placeholder="First Name" required/>
                </label>

                <label class="form_label"><i class="fas fa-user"></i>
                    <input id="userName" class="form_text" tabindex="3" type="text" name="username" placeholder="Username" required/>
                </label>

                <label class="form_label"><i class="fas fa-envelope"></i>
                    <input id="password" type="text" tabindex="4" class="form_text" name="password" placeholder="Password" autocomplete="off" required />
                </label>

                <label class="form_label"><i class="fas fa-envelope"></i>
                    <input id="userEmail" type="text" tabindex="4" class="form_text" name="email" placeholder="Email" autocomplete="off" required pattern="email" />
                </label>


                <!--label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="password" class="form_text" name="password" placeholder="Password" />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="password" class="form_text" name="confirm_password" placeholder="Confirm Password" />
                </label-->

                <select id="access" tabindex="5" name="access" required>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                </select>

<button type="submit" tabindex="6" class="button btn-post" id="save">Save User</button>

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
