<?php require(HEAD); ?>

<div id="myModal" class="img_modal">
  <!-- The Close Button -->
  <span class="close_btn">&times;</span>
  <!-- Modal Content (The Image) -->
  <img class="modal_content" id="img01">
</div>

<div class="container-fluid home_container">
    <div class="row">
        <div class="offset-lg-7 col-lg-5">
            <div class="login_container">
                <h4>Login</h4>
                <!---Reusable button--->
                <div class="gradient_button">
                    <a href="/signup.php">
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
            <a href="/newclub.php">
                <i class="fas fa-users"></i>
                <h2>New Club</h2>
                <p>Start a new club</p>
            </a>
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
                </div>
            </div>
        </div>
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
                </div>
            </div>
        </div>
    </div>
</div>

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
