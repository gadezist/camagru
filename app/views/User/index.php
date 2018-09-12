<?php if(isset($_SESSION['error'])): ?>
	<div class="alert-danger">
		<?php echo $_SESSION['error']; unset($_SESSION['error']) ?>
	</div>
<?php endif; ?>

<?php if(isset($_SESSION['success'])): ?>
	<div class="alert-success">
		<?=$_SESSION['success']; unset($_SESSION['success']) ?>
	</div>
<?php endif; ?>

<div class="container-user">
	<div id="frame-div" class="frame">
		<div class="nav">
			<ul class="links">
				<li class="signin-active" id="btn-in"><a id="btn" onclick="toggle()">Sing in</a></li>
				<li class="signup-inactive" id="btn-up"><a id="btn2" onclick="toggle()">Sing up</a></li>
			</ul>
		</div>
		<div>
			<form id="form-in" class="form-signin" action="/user/signin" method="post" name="form">
				<label for="login">Login</label>
				<input class="form-styling" type="text" name="login" placeholder=""/>
				<label for="password">Password</label>
				<input class="form-styling" type="password" name="password" placeholder=""/>
				<div class="btn-animate">
					<a class="btn-signin" onclick="document.getElementById('form-in').submit(); return false;">Sign in</a>
				</div>
			</form>
			<form id="form-up" class="form-signup" action="/user/signup" method="post" name="form">
				<label for="fullname">Login</label>
				<input class="form-styling" type="text" name="login" placeholder="" value="<?=isset($_SESSION['form_data']['login']) ? h($_SESSION['form_data']['login']) : '';?>"/>
				<label for="email">Email</label>
				<input class="form-styling" type="text" name="email" placeholder="" value="<?=isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : '';?>"/>
				<label for="password">Password</label>
				<input class="form-styling" type="password" name="password" placeholder=""/>
				<label for="confirmpassword">Name</label>
				<input class="form-styling" type="text" name="name" placeholder="" value="<?=isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : '';?>"/>
				<a class="btn-signup" onclick="document.getElementById('form-up').submit(); return false;">Sign Up</a>
			</form>
			<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
			
		</div>
		<div class="forgot" id="btn-forgot">
			<a href="/user/recovery">Forgot your password?</a>
		</div>
	</div>
</div>