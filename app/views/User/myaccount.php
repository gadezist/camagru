
<div class="container-user">
	<div id="frame-div" class="frame-profile">
		<div class="nav">
			<ul class="links">
				<li class="signin-active" id="btn-in"><a>Profile</a></li>
			</ul>
		</div>
		<div>
			<form id="profile_id" class="form-signin" action="/user/saveaccount" method="post" name="form">
				<label for="login">Login</label>
				<input class="form-styling" type="text" name="login" placeholder=<?=$_SESSION['user']['login']; ?> />
				<label for="login">Name</label>
				<input class="form-styling" type="text" name="name" placeholder=<?=$_SESSION['user']['name']; ?> />
				<label for="login">Email</label>
				<input class="form-styling" type="text" name="email" placeholder=<?=$_SESSION['user']['email']; ?> />
				<label for="password">Old Password</label>
				<input class="form-styling" type="password" name="old_password" placeholder="******"/>
				<label for="password">New Password</label>
				<input class="form-styling" type="password" name="new_password" placeholder=""/>
				<input type="checkbox" name="sendemail" id="sendemail" style="float: left; margin-right: 5px;" <?=$check = $_SESSION['user']['sendemail'] ? 'checked' : ''; ?> >
				<label for="sendemail" style="padding: 0;">send email notifications when commenting on your photo</label>
				<div class="btn-animate">
					<a class="btn-signin" onclick="document.getElementById('profile_id').submit(); return false;">Save</a>
				</div>
			</form>
		</div>
	</div>
</div>