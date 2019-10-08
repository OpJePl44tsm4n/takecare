<?php 

// these need to be loaded before include
// $video_url = get_field('video');
// $image_id = get_field('image');

$position = strrpos($video_url, 'v=');
$position = $position ? $position + 2 : strrpos($video_url, '/') + 1; // check what youtbe url is inserted
$video_id = substr($video_url, $position);

if ($video_id !== '') { ?>

<div class="video-inline">
	<div class="video-wrapper" data-player="player-<?php echo $image_id; ?>" data-video-id="<?php echo $video_id; ?>">
		<?php echo wp_get_attachment_image( $image_id, 'large' ); ?>
        <div class="video-element" 
            data-video-id="<?php echo $video_id; ?>"
            id="player-<?php echo $image_id; ?>">
        </div>
    </div>
</div>

<?php } ?> 
