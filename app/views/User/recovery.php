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
		<div>
			<form id="form-recovery" class="form-signin" action="/user/continue" method="post" name="form">
				<label for="login">Email</label>
				<input class="form-styling" type="text" name="email" placeholder=""/>
				<div class="btn-animate">
					<a class="btn-signin" onclick="document.getElementById('form-recovery').submit(); return false;">Continue</a>
				</div>
			<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>

		</div>
	</div>
</div>