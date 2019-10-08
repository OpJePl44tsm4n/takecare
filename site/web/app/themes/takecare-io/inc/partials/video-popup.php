<?php 

// these need to be loaded before include
// $video_url = get_field('video');
// $video_image = get_field('image');

$position = strrpos($video_url, 'v=');
$position = $position ? $position + 2 : strrpos($video_url, '/') + 1; // check what youtbe url is inserted
$video_id = substr($video_url, $position);

if ($video_id !== '') { ?>

<div class="video-popup">
	<div class="close-btn">
        <button class="cart-toggler ml-auto close" type="button" data-toggle="collapse" data-target="#videoCollapse" aria-controls="videoCollapse" aria-expanded="true" aria-label="Close cart"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="video-wrapper" 
    	data-player="player-<?php echo $image_id; ?>" 
    	data-video-id="<?php echo $video_id; ?>"
    	data-toggle="collapse" 
    	data-target="#videoCollapse">

        <?php echo wp_get_attachment_image( $image_id, 'large' ); ?>

		<div id="videoCollapse" class="collapse in">
			<div class="video-element" 
                id="player-<?php echo $image_id; ?>" 
                data-video-id="<?php echo $video_id; ?>">
            </div>	
		</div>	
    </div>
</div>

<?php } ?> 
