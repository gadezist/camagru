<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php \vendor\core\base\View::getMeta()?>
	<link href="../../../css/main.css" rel="stylesheet" type="text/css">
	<link href="../../../css/modal.css" rel="stylesheet" type="text/css">
	
</head>
<body>
	<div class="container" id="cont">
		<header>
			<div>
				<a href="/"><h1 align="center">CAMAGRU</h1></a>
			</div>
			<div class="username">
				<?php if(isset($_SESSION['user'])): ?>
					<div>Hello, <?php echo $_SESSION['user']['name']; ?></div>
					<a href="/user/myaccount">My account</a>
					<a href="/user/myimg">My images</a>
					<a href="/user/logout">Logout</a>
				<?php else: ?>
					<a href="/user/index">Sing in/Sing up</a>
				<?php endif; ?>
			</div>
		</header>
		<div class="content">
			<div class="left-side">
				<div class="video">
					<div class="play" id="video_play">
						<video id="video" width="640" height="480"></video>
						<img id="plus" src="images/plus.png">
						<img id="minus" src="images/minus.png">
					</div>
					<div class="btn">
						<input type="button" value="Record" onClick="enter()">
						<input type="file" id="file" name="upload">
						<?php if(isset($_SESSION['user'])): ?>
							<input type="button" id="delete_img" value="Delete">
							<input type="button" id="delete_all" value="Delete all">
						<?php endif; ?>
						<?php if(isset($_SESSION['user'])): ?>
							<input type="button" id="snap-user" value="Snap Photo" disabled="disabled">
						<?php else: ?>
							<input type="button" id="snap" value="Snap Photo" disabled="disabled">
						<?php endif; ?>
						
					</div>
					<section id="modal">
						<div class="modal-content">
							<canvas id="canvas" width="640" height="480"></canvas>
							<button id="save">Save</button>
							<button id="cancel">Cancel</button>
						</div>
						
					</section>
					<section id="modal-comments">
						<form class="modal-comments">
							<img id="image" src="#" width="640px" height="480px">
							<textarea id="text" maxlength="500" placeholder="Please leave a comment max of 500 characters" autofocus></textarea>
							<div class="buttons-comment">
								<button id="btn-comment">Send</button>
								<button id="btn-cancel">Cancel</button>
							</div>
						</form>
						
					</section>
				</div>
				<div class="add-images">
					<?php 
						$dir = "images/scr/";
						$files = scandir($dir);
 						for($i = 0; $i < count($files); $i++) {
 							$image = $dir . $files[$i];
 							if ($files[$i] != '.' && $files[$i] != '..') {
 								if(exif_imagetype($image)) {
 									echo "<div class='scr-image' id=$i onClick='add_image(this)'>";
 									echo "<img src={$image} width='120px' >";
 									echo "</div>";
 								}
 							}
 						}
					?>
				</div>
			</div>
			<div class="side" id="scr">
				<?php echo $content; ?>
			</div>
		</div>
		<div class="footer">
			<p>&copy2018 AbytkoCompany. All rights reserved</p>
		</div>
		<?php unset($_SESSION['success'])?>
	</div>
	<script type="text/javascript" src="/web/js/add_image.js"></script>
	<script type="text/javascript" src="/web/js/video.js"></script>
	<script type="text/javascript" src="/web/js/modal.js"></script>
	<script type="text/javascript" src="/web/js/like.js"></script>
	
</body>
</html>