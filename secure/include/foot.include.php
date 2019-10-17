    </main>
    <footer id="footer" class="row expanded footer">
        <div class="row first">
            <div class="columns small-12 medium-6 large-3">
                <h6>Contact</h6>
                <p><strong>t:</strong> <a href="tel:+613<?= PHONE; ?>"
                                          title="Call us" class="text-link nowrap">(03) <?= PHONE; ?></a><br/>
                   <strong>e:</strong> <a href="mailto:<?= CONTACT_EMAIL; ?>"
                                           title="email us" class="text-link nowrap"><?= CONTACT_EMAIL; ?></a>
                </p>
            </div>
            <div class="columns small-12 medium-6 large-3">
                <h6>Location</h6>
                <p><?= ADDRESS; ?>,<br/>
                <?= SUBURB; ?>, <?= STATE; ?>, <?= POSTCODE; ?><br/>
                <?= COUNTRY; ?></p>
            </div>
            <div class="columns small-12 medium-6 large-3">
                <h6>Hours</h6><!-- TODO Gordon add to config -->
                <p>Mon - Fri 8am to 5:30pm<br>
                    Sat - Sun 8am to 5:30pm
                </p>
            </div>
            <div class="columns small-12 medium-6 large-3">
                <div class="footer-logo">
                    <a class="" href="/" title="Company brand name - Home"><img src="<?= ROOT; ?>web/image/demo/rare_logo.png" alt="Company brand name"></a>
                </div>
            </div>
        </div>
        <div class="row last">
            <div class="columns small-12 medium-10 large-10">
                <ul class="social-list"> <!-- TODO Gordon add to config and calculated year -->
                    <li><a href="#" target="_blank" class="text-link"><i class="icons icons-twitter xsmall"></i></a></li>
                    <li><a href="#" target="_blank" class="text-link"><i class="icons icons-facebook xsmall"></i></a></li>
                    <li><a href="#" target="_blank" class="text-link"><i class="icons icons-instagram xsmall"></i></a></li>
                </ul>
                <p class="copyright">&copy; 2017 Carne Grill | Eastridge Entertainment Precinct</p> <!-- TODO Gordon add to config and calculated year -->
            </div>
            <div class="columns small-12 medium-2 large-2">
                <p class="siteby">
                    <a href="http://rare.com.au" target="_blank" title="Raremedia - A relaxed digital creative agency based in Richmond Melbourne">
                        <span class="icon-label">site by RARE </span><i class="icons icons-raremedia xsmall"></i>
                    </a>
                </p>
            </div>
        </div>
    </footer>
</div>

<!-- TODO: Remove Overview mark up if site does not require overview -->

<!-- NOTE: START overview mark up -->
<div id="overview1" class="overview overview1">
    <a class="overview-close"><i class="icons icons-close xsmall"></i></a>
    <div class="overview-inner">
    </div>
</div>

<div id="overview2" class="overview overview2">
    <a class="overview-close"><i class="icons icons-close xsmall"></i></a>
    <div class="overview-inner">
    </div>
</div>

<div id="overview3" class="overview overview3">
    <a class="overview-close"><i class="icons icons-close xsmall"></i></a>
    <div class="overview-inner">
    </div>
</div>

<div id="overview4" class="overview overview4">
    <a class="overview-close"><i class="icons icons-close xsmall"></i></a>
    <div class="overview-inner">
    </div>
</div>
<!-- NOTE: END overview mark up -->

<script>
    var gaCode = "<?php echo GA_TRACK_ID; ?>";
</script>
    <?= $web_compile->build_js(FOOT_JS, 'foot'); ?>

</body>
</html>
