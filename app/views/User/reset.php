<?php if(isset($_SESSION['error'])): ?>
	<div class="alert-danger">
		<?php echo $_SESSION['error']; unset($_SESSION['error']) ?>
	</div>
<?php endif; ?>

<div class="container-user">
	<div id="frame-div" class="frame">
		<div>
			<form id="form-recovery" class="form-signin" action="/user/resetPassword" method="post" name="form">
				<label for="login">New Password</label>
				<input class="form-styling" type="password" name="new_password" placeholder=""/>
				<label for="login">Confirm Password</label>
				<input class="form-styling" type="password" name="confirm_password" placeholder=""/>
				<div class="btn-animate">
					<a class="btn-signin" onclick="document.getElementById('form-recovery').submit(); return false;">Confirm</a>
				</div>
			<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>

		</div>
	</div>
</div>