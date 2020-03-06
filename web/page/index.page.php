<?php require(HEAD); ?>

<div id="myModal" class="img_modal">
  <!-- The Close Button -->
  <span class="close_btn">&times;</span>
  <!-- Modal Content (The Image) -->
  <img class="modal_content" id="img01">
</div>


<div id="new-bet" class="overlay">
	<div class="popup">
		<h2>New Bet Slip</h2>
		<!--a class="close" href="#"><i class="fas fa-times"></i></a-->
        <hr />
		<div class="content">
			<form id="new_bet_form" accept-charset="UTF-8" enctype="multipart/form-data" name="new_bet_form" action="<?=$shortcut->clean_uri($_SERVER['REQUEST_URI']);?>" method="post">
				<label class="form_label"><i class="fas fa-user"></i>
					<select required class="form_text" name="user">
						<option value="">Please Select</option>
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
				<!--div class="form-group">
					<label class="form-label" for="desc"><i class="fas fa-comment-dollar"></i>&nbsp;&nbsp;Description</label>
					<input id="desc" class="form-input" name="description" type="text" />
				</div-->
                <label class="form_label"><i class="fas fa-comment-dollar"></i>
                    <input type="text" class="form_text" name="description" placeholder="Description" />
                </label>
                <label class="form_label"><i class="fas fa-dollar-sign"></i>
                    <input required type="number" class="form_text" step="0.01" name="odds" placeholder="Odds" />
                </label>
                <label class="form_label"><i class="fas fa-dollar-sign"></i>
                    <input required type="number" class="form_text" step="0.01" name="amount" placeholder="Amount" />
                </label>
				<label class="form_label">
					<input type="file" name="file" id="file" class="inputfile" data-multiple-caption="{count} files selected" multiple />
					<label for="file"><span><i class="fas fa-folder-open"></i> Bet Slip Screenshot</span></label>
                </label>
				<label class="form_label">
					<input name="bonusbet" class="form-checkbox" type="checkbox" value="bb" /> <span>Bonus Bet</span>
				</label>
				<input type="hidden" name="action" value="new_bet"/>
                <div class="sign_up_buttons">
					<button type="submit">
	                    <div class="gradient_button">
	                        <a href="" id="new_bet_submit">
	                            <div class="button_content">
	                                <i class="fas fa-plus"></i>
	                                <div class="vertical_line"></div>
	                                <h5>Add Bet</h5>
	                            </div>
	                        </a>
	                    </div>
					</button>
                    <div class="cancel_new-bet">
                        <p>Cancel</p>
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
        <div class="col-sm-3 main_button">
            <i class="fas fa-football-ball"></i>
            <h2>My Clubs</h2>
            <p>View and edit your existing clubs</p>
        </div>
        <div class="col-sm-3 main_button">
            <i class="fas fa-users"></i>
            <h2>New Club</h2>
            <p>Start a new club</p>
        </div>
        <div class="col-sm-3 main_button">
            <i class="fas fa-trophy"></i>
            <h2>My Awards</h2>
            <p>View your accomplishments</p>
        </div>
        <div class="col-sm-3 main_button">
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
                    <h1><?=$myClubname?></h1>
                    <p>Total Won: $<?=number_format((float)$totalWon, 2, '.', '')?></p>
					<p>Total Bet: $<?=number_format((float)$total, 2, '.', '')?></p>
                    <?=$roi?>
                    <!--p>Bank: $000.00</p-->
					<p>Bonus Bets: Won $<?=number_format((float)$totalWonBB, 2, '.', '')?> out of $<?=number_format((float)$totalBB, 2, '.', '')?></p>
                    <p>Total: $<?=number_format((float)$totalWon, 2, '.', '')?></p>
					<p>Week Starts: <?=$weekStarts?></p>
                    <p><?=$lowestOddsLostText?></p>
                    <p><?=$highestOddsWonText?></p>
                    <p><?=$highestAmountWonText?></p>
                    <p><?=$highestOddsText?></p>
                    <p><?=$winStreakText?></p>
                    <p><?=$lossStreakText?></p>
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
						<?=$betters_this_week?>
					</ul>
					<ul class="next_up_punters_list">
						<?=$betters_next_week?>
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
				<!--h3>GRAPHS</h3-->
				<h3>AWARDS</h3>
			</div>
			<!--div class="leaderboard_header">
				<h4>Name</h4>
				<h4>ROI</h4>
				<h4>Wagered</h4>
				<h4>Won</h4>
				<h4>Form</h4>
			</div-->
			<div class="tables_graphs_awards">
				<?=$table?>
				<!--div class="graphs">
					<div id="curve_chart"></div>
				</div-->
                <!--div class="awards_container">
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Highest Odds Bet</h5>
                        <p>$5.34</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge2.png" class="my_badge" />
                        <h5>Highest Odds Won</h5>
                        <p>$5.34</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Lowest Odds Lost</h5>
                        <p>$1.34</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Contrubutor</h5>
                        <p>5 Wins in a row</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Look at me...</h5>
                        <p>10 Wins in a row</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>I am the Captain now</h5>
                        <p>Top the leaderboard</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Loser</h5>
                        <p>Last Place at least once</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Dead Weight</h5>
                        <p>5 Losses in a row</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Just Stop</h5>
                        <p>10 Losses in a row</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Biggest Win</h5>
                        <p>$10.54</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Double Down</h5>
                        <p>Reach 200% ROI</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Longest Win Streak</h5>
                        <p>4 Wins</p>
                    </div>
                    <div class="award">
                        <img src="/web/image/badges/badge1.png" class="my_badge" />
                        <h5>Longest Loss Streak</h5>
                        <p>6 Losses</p>
                    </div>
                </div-->
			</div>
		</div>
	</div>
</div>
<div class="container-fluid current_bets_container">
    <div class="row">
        <div class="col-md-9">
			<h3>CURRENT BETS</h3>
		</div>
		<div class="col-md-3 chat_header">
			<h3>REPORT</h3>
		</div>
		<div class="col-md-9">
			<div class="bets_container">
				<?=$pending_bets;?>
				<div class="bet_slip_container new_bet new_bet_btn">
		            <div class="vertical_gradient">
		                <div class="bet_slip new_bet">
							<i class="fas fa-plus"></i>
		                </div>
						<div class="pending_detail">
				        	<h3>Add New Bet</h3>
				        </div>
		            </div>
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
                    <?=$weekSummary?>
				</div>
			</div>
		</div>
	</div>
</div>

  <!-- content end -->

<?php require(FOOT); ?>
<!--script type="text/javascript">
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
  data: {
    labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
    datasets: [{
        data: [86,114,106,106,107,111,133,221,783,2478],
        label: "Africa",
        borderColor: "#3e95cd",
        fill: false
      }, {
        data: [282,350,411,502,635,809,947,1402,3700,5267],
        label: "Asia",
        borderColor: "#8e5ea2",
        fill: false
      }, {
        data: [168,170,178,190,203,276,408,547,675,734],
        label: "Europe",
        borderColor: "#3cba9f",
        fill: false
      }, {
        data: [40,20,10,16,24,38,74,167,508,784],
        label: "Latin America",
        borderColor: "#e8c3b9",
        fill: false
      }, {
        data: [6,3,2,2,7,26,82,172,312,433],
        label: "North America",
        borderColor: "#c45850",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'World population per region (in millions)'
    }
  }
});
</script-->
