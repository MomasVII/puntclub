<?php require(HEAD); ?>

<div class="wrapper gray-light-bg manage-users">

    <div class="row small-collapse">
        <div class="columns small-6">
            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
        </div>
        <div class="columns small-6">
            <a href="<?=LOCAL;?>manage_users.html" class="button page-title btn-green float-right gray-dark-bg">Return to Manage Users</a>
        </div>
    </div>

    <div class="row content-block">

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

            <div class="columns small-12">
                <p>
                    <small>* Required fields</small>
                </p>
            </div>

            <div class="columns small-12">

                <label for="firstName">First name&nbsp;*</label>
                <input id="firstName" tabindex="1" type="text" name="first_name" required/>

                <label for="lastName">Last name&nbsp;*</label>
                <input id="lastName" tabindex="2" type="text" name="last_name" required/>

                <label for="userName">Username&nbsp;*</label>
                <input id="userName" tabindex="3" type="text" name="username" autocomplete="off" required/>

                <label for="userEmail">Email address&nbsp;*</label>
                <input id="userEmail" tabindex="4" type="email" name="email" autocomplete="off" required pattern="email"/>


                <label for="access">Access level&nbsp;*</label>
                <select id="access" tabindex="5" name="access" required>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                </select>

                <label>&nbsp;</label>
                <button type="submit" tabindex="6" class="button btn-post" id="save">Save User</button>
            </div>

        </form>

    </div>

</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
