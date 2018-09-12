<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Default</title>
	<link href="../../../css/main.css" rel="stylesheet" type="text/css">
	<link href="../../../css/user.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../../js/user.js"></script>
</head>
<body>
	<div class="container">
		<header>
			<div>
				<a href="/"><h1 align="center" class="camagru">CAMAGRU</h1></a>
			</div>
			<div class="username">
				<?php if(isset($_SESSION['user'])): ?>
					<p>Hello, <?php echo $_SESSION['user']['name']; ?></p>
					<a href="/user/myaccount">My account</a>
					<a href="/user/myimg">My images</a>
					<a href="/user/logout">Logout</a>
				<?php else: ?>
					<a href="/user/index">Sing in/Sing up</a>
				<?php endif; ?>
			</div>
		</header>

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

		<?php echo $content; ?>

	</div>
</body>
</html>