<?php require(HEAD); ?>

<div class="wrapper gray-light-bg manage-users">

    <form data-abide novalidate role="form" action="<?= $shortcut->clean_uri($_SERVER['REQUEST_URI']); ?>?id=<?= $user_data['user_id']; ?>" method="post" enctype="multipart/form-data" id="update">

		<div id="waitSpinner" class="spinner-wrapper">
			<div class="spinner-mask">
				<div class="spinner-container">
					<div class="spinner"></div>
				</div>
			</div>
		</div>

	    <div class="row small-collapse">
	        <div class="columns small-4">
	            <h1 class="gray-darker page-title"><?=PAGE;?></h1>
	        </div>
	        <div class="columns small-8">
	            <a href="<?=LOCAL;?>manage_users.html" class="button page-title btn-green float-right gray-dark-bg">Return to Manage Users</a>
	            <button id="reset_password_btn" type="submit" name="action" value="reset_password" class="button page-title btn-green float-right gray-dark-bg">Reset Password</button>
	        </div>
	    </div>

	    <div class="row content-block">

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
                <input id="firstName" tabindex="0" type="text" name="first_name" value="<?= $validate->encode_html($user_data['first_name']); ?>" required/>

                <label for="lastName">Last name&nbsp;*</label>
                <input id="lastName" tabindex="1" type="text" name="last_name" value="<?= $validate->encode_html($user_data['last_name']); ?>" required/>

                <label for="userName">Username&nbsp;*</label>
                <input id="userName" tabindex="2" type="text" name="username" autocomplete="off" value="<?= $validate->encode_html($user_data['username']); ?>" required/>

                <label for="userEmail">Email address&nbsp;*</label>
                <input id="userEmail" tabindex="3" type="email" name="email" value="<?= $validate->encode_html($user_data['email']); ?>" autocomplete="off" required pattern="email"/>

                <label for="access">Access level&nbsp;*</label>
                <select id="access" tabindex="4" name="access" required>
                    <option value="admin" <?php if($user_data['access'] === 'admin' && $user_data['disabled'] === 0){echo 'selected="selected"';}?>>Admin</option>
                    <option value="manager" <?php if($user_data['access'] === 'manager' && $user_data['disabled'] === 0){echo 'selected="selected"';}?>>Manager</option>
                    <option value="disabled" <?php if($user_data['disabled'] === 1){echo 'selected="selected"';}?>>Disabled</option>
                </select>

                <label>&nbsp;</label>
                <button type="submit" class="button btn-post" id="save" name="action" value="update">Save User</button>

            </div>

        </div>

    </form>
</div>
<!-- END wrapper -->
<?php require(FOOT); ?>
