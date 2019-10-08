<?php 

if ($video_id !== '') { ?>

<div class="video-animation">
	<video autoplay poster="<?php echo wp_get_attachment_image_url( $image_id, 'large' ); ?>">
		<source src="<?php echo wp_get_attachment_url( $video_id ); ?>" type="video/mp4">
		<p>This browser does not support the video element.</p>
	</video>
</div>

<?php } ?> 
