    </main>
    <footer id="footer" class="row expanded footer">

        <div class="container-fluid responsive_footer">
            <div class="row">
                <div class="col-sm-12 footer_col">
                    <div class="footer_item new_bet_btn">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>New Bet</p>
                    </div>
                    <div class="footer_item">
                        <i class="fas fa-comments-dollar"></i>
                        <p>Chat</p>
                    </div>
                    <div class="footer_item">
                        <i class="fas fa-football-ball"></i>
                        <p>My Clubs</p>
                    </div>
                    <div class="footer_item">
                        <i class="fas fa-tools"></i>
                        <p>Settings</p>
                    </div>
                </div>
            </div>
        </div>

    </footer>
</div>

<script>
    var gaCode = "<?php echo GA_TRACK_ID; ?>";
</script>
    <?= $web_compile->build_js(FOOT_JS, 'foot'); ?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</body>
</html>
