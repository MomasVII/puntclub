<?php require(HEAD); ?>


<div id="sign-up" class="overlay">
	<div class="popup">
		<h2>Sign Up</h2>
        <p>Australias premium punt club management system</p>
		<!--a class="close" href="#"><i class="fas fa-times"></i></a-->
        <hr />
		<div class="content">
			<form>
                <label class="form_label"><i class="fas fa-user"></i>
                    <input type="text" class="form_text" name="name" placeholder="Name" />
                </label>
                <label class="form_label"><i class="fas fa-envelope"></i>
                    <input type="text" class="form_text" name="email" placeholder="Email" />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="text" class="form_text" name="password" placeholder="Password" />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="text" class="form_text" name="confirm_password" placeholder="Confirm Password" />
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
                    <div class="cancel_sign-up">
                        <a href="#">
                            <p>Cancel</p>
                        </a>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>

<div class="container-fluid home_container">
    <div class="row">
        <div class="offset-lg-7 col-lg-5">
            <div class="login_container">
                <h4>Login</h4>
                <!---Reusable button--->
                <div class="gradient_button">
                    <a href="#sign-up">
                        <div class="button_content">
                            <i class="fas fa-user"></i>
                            <div class="vertical_line"></div>
                            <h5>Sign Up</h5>
                        </div>
                    </a>
                </div>
                <!---Button end--->
            </div>
        </div>
    </div>
    <div class="punt_club_banner">
        <h1>PUNT.CLUB</h1>
        <p>Punt Club Management</p>
    </div>
    <div class="row home_buttons">
        <div class="col-lg-3 main_button">
            <i class="fas fa-football-ball"></i>
            <h2>My Clubs</h2>
            <p>View and edit your existing clubs</p>
        </div>
        <div class="col-lg-3 main_button">
            <i class="fas fa-users"></i>
            <h2>New Club</h2>
            <p>Start a new club</p>
        </div>
        <div class="col-lg-3 main_button">
            <i class="fas fa-trophy"></i>
            <h2>My Awards</h2>
            <p>View your accomplishments</p>
        </div>
        <div class="col-lg-3 main_button">
            <i class="fas fa-tools"></i>
            <h2>Club Settings</h2>
            <p>Change your club settings</p>
        </div>
    </div>
</div>
<div class="container-fluid club_details_container">
    <div class="row">
        <div class="col-md-6">
            <div class="vertical_gradient">
                <div class="club_details">
                    <h1>MY PUNT CLUB</h1>
                    <p>ROI: <span>148.54%</span></p>
                    <p>Total Won: 1432.65</p>
                    <p>Bank: $543.63</p>
                    <p>Total: $1965.63</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 tabs_col">
			<div class="tab_container">
				<div class="tab_headers">
					<h3 class="active">Current Punters</h3>
					<h3>Next Up</h3>
				</div>
				<div class="punter_list">
					<ul class="current_punters_list">
						<li>Thomas Bye</li>
						<li>Lachy Pound</li>
						<li>Simon Jackson</li>
						<li>Alistair Holliday</li>
						<li>Angus Hillman</li>
					</ul>
					<ul class="next_up_punters_list">
						<li>Thomas Bye</li>
						<li>Lachy Pound</li>
						<li>Simon Jackson</li>
						<li>Alistair Holliday</li>
					</ul>
				</div>
			</div>
        </div>
    </div>
</div>

  <!-- content end -->

<?php require(FOOT); ?>
