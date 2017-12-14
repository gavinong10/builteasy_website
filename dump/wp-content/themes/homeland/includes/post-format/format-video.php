<?php
	$homeland_video = get_post_meta( $post->ID, "homeland_video", true );
	$homeland_video_host = get_post_meta( $post->ID, "homeland_video_host", true );

	if($homeland_video_host == "self") : ?>
		<video id="videojs_blog" class="video-js vjs-default-skin" controls preload="none" width="100%" height="500" poster="<?php echo $homeland_large_image_url[0]; ?>" data-setup="{}">
			<source src="<?php echo $homeland_video; ?>" type='video/mp4' />
		    <source src="<?php echo $homeland_video; ?>" type='video/webm' />
		    <source src="<?php echo $homeland_video; ?>" type='video/ogg' />
		</video><?php
	else : ?>
		<iframe width="100%" height="500" src="<?php echo $homeland_video; ?>" frameborder="0" allowfullscreen class="sframe"></iframe><?php
	endif;
?>