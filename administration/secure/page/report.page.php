<?php require(HEAD); ?>

<div class="wrapper gray-light-bg reports">

	<div id="waitSpinner" class="spinner-wrapper fixed">
		<div class="spinner-mask">
			<div class="spinner-container">
				<div class="spinner"></div>
			</div>
		</div>
	</div>

    <div class="bg">
        <img src="<?=LOCAL;?>web/image/svg/auspost-reports01a.svg" alt="Reports page envelope image"/>
    </div>

    <div class="row">
        <div class="columns small-12">
            <h1 class="gray-darker page-title">Reporting Metrics</h1>
        </div>
    </div>

    <div class="row">
        <div class="columns small-12">
            <div id="response" data-abide-error class="alert callout" style="display: none;">
                <p><i class="fi-alert"></i> <?=$response;?> </p>
            </div>
        </div>
    </div>

    <div class="row report-row">

        <div class="columns small-12 medium-4">

            <div class="report-btn-group">

                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?current_subscribers=1" class="primary-cta" title="Download current subscribers">
                    <span>Current Subscribers</span>
                    <i class="icons icons-download xsmall"></i>
                </a>
                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?expired_subscribers=1" class="primary-cta" title="Download expired subscribers">
                    <span>Expired Subscribers</span>
                    <i class="icons icons-download xsmall"></i>
                </a>
                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?free_download_data=1" class="primary-cta" title="Download free tier download log rows (large file, be patient)">
                    <span>Free Download Data</span>
                    <i class="icons icons-download xsmall"></i>
                </a>
                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?paid_download_data=1" class="primary-cta" title="Download paid tier download log rows (large file, be patient)">
                    <span>Paid Download Data</span>
                    <i class="icons icons-download xsmall"></i>
                </a>
                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?email_log=bounce" class="primary-cta" title="Download all email bounce data">
                    <span>Download Email<br/> Bounce Data</span>
                    <i class="icons icons-download xsmall"></i>
                </a>
                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?email_log=complaint" class="primary-cta" title="Download all email complaint data">
                    <span>Download Email<br/> Complaint Data</span>
                    <i class="icons icons-download xsmall"></i>
                </a>

            </div>

            <div>

                <a href="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']);?>?action=download_marketing" class="primary-cta">
                    <span>Download Marketing<br/> Template &amp; Data</span>
                    <i class="icons icons-download xsmall"></i>
                </a>

                <form data-abide novalidate name="marketing_template_form" id="marketing_template_form" action="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']);?>?" method="post" enctype="multipart/form-data">

                    <input name="action" value="upload_marketing" class="hide"/>

                    <fieldset class="file-upload">
                        <input type="file" name="marketing_template_file" id="marketing_template_file" class="inputfile" data-multiple-caption="{count} files selected" multiple>
                        <label for="marketing_template_file">
                            <strong>Choose file</strong>
                            <span>No file chosen</span>
                        </label>
                    </fieldset>

                    <button type="submit" class="primary-cta" id="btn_submit">
                        <span>Upload Marketing <br/>Template &amp; Data</span>
                        <i class="icons icons-upload xsmall"></i>
                    </button>

                </form>
            </div>

        </div>

        <div class="columns small-12 medium-4">

            <div class="card">
                <span><?= $metrics['revenue_this_month']; ?></span>
                <p>Revenue since the 1st of <?=date('F');?></p>
            </div>

            <div class="card">
                <span><?= $metrics['revenue_this_year']; ?></span>
                <p>Revenue this financial year to date</p>
            </div>

            <div class="card">
                <span><?= $metrics['current_subscribers']; ?></span>
                <p>Total current subscribers</p>
            </div>

            <ul>
                <li>
                    <span class="dataHero"><?= $metrics['current_national']; ?></span>
                    <span>Current 'National' data-set subscribers</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['current_state']; ?></span>
                    <span>Current 'State' data-set subscribers</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['current_postcode']; ?></span>
                    <span>Current 'Postcode' data-set subscribers</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['subscribers_increase_percent']; ?></span>
                    <span>New subscribers this month vs last month</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['subscribers_this_month']; ?></span>
                    <span>New subscribers this month to date</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['subscribers_this_year']; ?></span>
                    <span>New subscribers this year to date</span>
                </li>
            </ul>

        </div>

        <div class="columns small-12 medium-4">

            <div class="card">
                <span><?= $metrics['revenue_last_30_days']; ?></span>
                <p>Revenue in the last 30 days</p>
            </div>

            <div class="card">
                <span><?= $metrics['revenue_this_month_last_year_increase_percent']; ?></span>
                <p><?=date('F Y');?> vs <?=date('F Y', strtotime('-1 year'));?></p>
            </div>

            <div class="card">
                <span><?= $metrics['renewed_subscribers_this_month']; ?></span>
                <p>Renewed subscribers this month to date</p>
            </div>

            <ul>
                <li>
                    <span class="dataHero"><?= $metrics['renewed_subscribers_this_year']; ?></span>
                    <span>Renewed subscribers this year to date</span>
                </li>
                <li><span class="dataHero"><?= $metrics['expired_this_month']; ?></span>
                    <span>Expired subscribers this month to date</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['expired_this_year']; ?></span>
                    <span>Expired subscribers this year to date</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['email_bounces_month']; ?></span>
                    <span>Email bounces this month</span>
                </li>
                <li>
                    <span class="dataHero"><?= $metrics['email_complaints_month']; ?></span>
                    <span>Email complaints this month</span>
                </li>
            </ul>

        </div>

    </div>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
