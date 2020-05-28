
<section class="map-wrap" style="background:#73b6e6;">
    <div id="map" 
        data-lat="<?php echo $focus_point['lat']; ?>" 
        data-lng="<?php echo $focus_point['lng']; ?>" 
        data-zoom="<?php echo $zoom_level; ?>"
        data-popup='<?php echo json_encode($popup_content) ?>'
        style="height:450px;"></div>
</section>

 <?php 
 wp_enqueue_style( 'mapbox', 'https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' );
 wp_enqueue_script( 'mapbox-load', 'https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js', array( 'jquery' ), '', true ); 
 wp_enqueue_script( 'mapbox-simple-init', trailingslashit( get_stylesheet_directory_uri()) .'assets/dist/js/mapbox-simple-init.js', array( 'jquery', 'mapbox-load' ), '', true ); 
    
 