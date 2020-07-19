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
        <h1>NEW.CLUB</h1>
        <p>Let's get going</p>
    </div>
    <div class="empty">
    </div>
</div>
<div class="container-fluid signup_form">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form accept-charset="UTF-8" enctype="multipart/form-data" name="new_club_form" action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>" method="post">
                <label class="form_label"><i class="fas fa-user"></i>
                    <input type="text" class="form_text" name="name" placeholder="Club Name" required />
                </label>
                <label class="form_label"><i class="fas fa-user"></i>
					<select required class="form_text" name="week_start" required>
						<option value="">Week Start Day</option>
						<option value="Monday">Monday</option>
						<option value="Tuesday">Tuesday</option>
						<option value="Wednesday">Wednesday</option>
						<option value="Thursday">Thursday</option>
						<option value="Friday">Friday</option>
						<option value="Saturday">Saturday</option>
						<option value="Sunday">Sunday</option>
					</select>
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="text" class="form_text" name="amount_bet" placeholder="Amount Bet per Week" required />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="text" class="form_text" name="roi_required" placeholder="ROI Required to Bet Again" required />
                </label>
                <label class="form_label"><i class="fas fa-unlock-alt"></i>
                    <input type="text" class="form_text" name="deposited" placeholder="Amount Being Deposited" required />
                </label>
                <input type="hidden" name="action" value="new_club"/>
                <button class="sign_up_buttons" type="submit">
                    <div class="gradient_button">
                        <div class="button_content">
                            <i class="fas fa-user"></i>
                            <div class="vertical_line"></div>
                            <h5>Create Club</h5>
                        </div>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>
