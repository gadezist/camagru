	<div class="my-photos">
		<?php
			foreach($_SESSION['screens'] as $id => $src) {
				echo "<div class='my-image' id=$id>";
 				echo "<img src=/$src width='320px'>";
 				echo "<div class='delete'>";
 				echo "<img src=/web/images/delete.png id='$id' onclick='del(this)'>";
 				echo "</div>";
 				echo "</div>";
		}
		?>
	</div>