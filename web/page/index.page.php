<?php require(HEAD); ?>


<div id="new-bet" class="overlay">
	<div class="popup">
		<h2>New Bet Slip</h2>
		<!--a class="close" href="#"><i class="fas fa-times"></i></a-->
        <hr />
		<div class="content">
			<form>
				<label class="form_label"><i class="fas fa-comment-dollar"></i>
					<select class="form_text">
						<option value="1">Simon Jackson</option>
						<option value="2">Thomas Bye</option>
						<option value="3">Lachlan Pound</option>
						<option value="4">Alistair Holiday</option>
						<option value="5">Angus Hillman</option>
						<option value="6">Calvin Bransdon</option>
						<option value="7">Joel Leegood</option>
						<option value="8">Tom Dann</option>
					</select>
                </label>
                <label class="form_label"><i class="fas fa-comment-dollar"></i>
                    <input type="text" class="form_text" name="name" placeholder="Description" />
                </label>
                <label class="form_label"><i class="fas fa-dollar-sign"></i>
                    <input type="number" class="form_text" name="email" placeholder="Odds" />
                </label>
                <label class="form_label"><i class="fas fa-dollar-sign"></i>
                    <input type="number" class="form_text" name="password" placeholder="Amount" />
                </label>

                <div class="sign_up_buttons">
                    <div class="gradient_button">
                        <a href="#sign-up">
                            <div class="button_content">
                                <i class="fas fa-plus"></i>
                                <div class="vertical_line"></div>
                                <h5>Add Bet</h5>
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
                    <p>Total Won: $<?=number_format((float)$totalWon, 2, '.', '')?></p>
					<p>Total Bet: $<?=number_format((float)$total, 2, '.', '')?></p>
                    <?=$roi?>
                    <p>Bank: $000.00</p>
                    <p>Total: $<?=number_format((float)$totalWon, 2, '.', '')?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 tabs_col">
			<div class="tab_container">
				<div class="tab_headers">
					<h3 class="active">CURRENT PUNTERS</h3>
					<h3>NEXT UP</h3>
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
<div class="container-fluid table_container">
    <div class="row">
        <div class="col-md-12 table_col">
			<div class="table_headers">
				<h3 class="active">LEADERBOARD</h3>
				<h3>GRAPHS</h3>
				<h3>AWARDS</h3>
			</div>
			<div class="leaderboard_header">
				<h4>Name</h4>
				<h4>ROI</h4>
				<h4>Wagered</h4>
				<h4>Won</h4>
				<h4>Form</h4>
			</div>
			<div class="leaderboard_content">
				<h4>Thomas Bye</h4>
				<h4>34%</h4>
				<h4>$123</h4>
				<h4>$34</h4>
				<div class="form">
					<div class="loss"></div>
					<div class="win"></div>
				</div>
			</div>
			<div class="leaderboard_content">
				<h4>Thomas Bye</h4>
				<h4>34%</h4>
				<h4>$123</h4>
				<h4>$34</h4>
				<div class="form">
					<div class="loss"></div>
					<div class="win"></div>
				</div>
			</div>
			<div class="leaderboard_content">
				<h4>Thomas Bye</h4>
				<h4>34%</h4>
				<h4>$123</h4>
				<h4>$34</h4>
				<div class="form">
					<div class="loss"></div>
					<div class="win"></div>
				</div>
			</div>
			<div class="leaderboard_content">
				<h4>Thomas Bye</h4>
				<h4>34%</h4>
				<h4>$123</h4>
				<h4>$34</h4>
				<div class="form">
					<div class="loss"></div>
					<div class="win"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid current_bets_container">
    <div class="row">
        <div class="col-md-9">
			<h3>CURRENT BETS</h3>
		</div>
		<div class="col-md-3">
			<h3>CHAT</h3>
		</div>
		<div class="col-md-9">
			<div class="bets_container">
				<?=$pending_bets;?>
				<div class="bet_slip_container" id="new-bet">
					<a href="#new-bet">
			            <div class="vertical_gradient">
			                <div class="bet_slip new_bet">
								<i class="fas fa-plus"></i>
			                </div>
							<div class="pending_detail">
					        	<h3>Add New Bet</h3>
					        </div>
			            </div>
					</a>
		        </div>
			</div>
			<h3 class="resulted_header">RESULTED BETS</h3>
			<div class="bets_container">
				<?=$resulted_bets?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="vertical_gradient">
				<div class="chat_container">
					<form class="chat_form">
						<textarea type="text" class="chat_box"></textarea>
						<div class="gradient_button grd_btn_small">
							<a href="#">
								<div class="button_content btn_small">
									<h5>Send</h5>
								</div>
							</a>
						</div>
					</form>
					<div class="other_chat">
						<h4 class="chatter">Thomas</p>
							<hr />
						<p>Agree. No one uses a BB on $1.50 odds. You have to double your money to get worth. As cal said, half their value</p>
					</div>
					<div class="other_chat">
						<h4 class="chatter">Lachy</p>
							<hr />
						<p>“Free money” syndrome</p>
					</div>
					<div class="other_chat">
						<h4 class="chatter">Lachy</p>
							<hr />
						<p>People throw bonus bets away afaik</p>
					</div>
					<div class="other_chat">
						<h4 class="chatter">Calvin</p>
							<hr />
						<p>I value bonus bets at around half their amount in cash</p>
					</div>
					<div class="my_chat">
						<h4 class="chatter">Calvin</p>
							<hr />
						<p>I value bonus bets at around half their amount in cash</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
  <!-- content end -->

<?php require(FOOT); ?>
