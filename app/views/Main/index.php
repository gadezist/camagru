<div class="screens">
	<?php if(!empty($screens)): ?>
		<?php foreach($screens as $screen): ?>
		<div class="image">
			<img src="<?=$screen['img_path'];?>" width="320px" height="240px">
			<div class="likes">
				
				<div>
					<a href="#" class="button-like" id="<?=$screen['id']; ?>" onclick="like(this)"><img src="web/images/like.png" 	width="50px" height="25px"></a>

				</div>
				<div class="count-like">
					<span scr_id="<?=$screen['id']; ?>"><?=$screen['count'];?></span>
				</div>
				<div class="comments" id="get-comment" >
					<a href="#" onclick="comment(this)" name="<?=$screen['img_path'];?>" id="<?=$screen['id']; ?>">Comments</a>
				</div>
			</div>
		</div>
		<?php endforeach; ?>

	<?php endif; ?>
</div>
<div class="pagin">
	<?php if($pagination->countPages > 1): ?>
		<?=$pagination; ?>
	<?php endif; ?>
</div>