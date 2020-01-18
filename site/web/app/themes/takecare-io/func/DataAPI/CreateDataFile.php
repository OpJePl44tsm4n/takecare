<?php 
namespace Greylabel\DataAPI;
use Greylabel\Greylabel;

class CreateDataFile {

	/**
    *   create a json file
    */
    public static function create_json_file($data, $file_name) 
    {
        $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . trailingslashit(Greylabel::THEME_SLUG);
		wp_mkdir_p( $uploads_dir );

		$file_location = $uploads_dir . $file_name;
   
    	$json_file = fopen($file_location, "w") or die("Unable to open file!");

		fwrite($json_file, json_encode($data, JSON_PRETTY_PRINT));
		fclose($json_file);
    }

    /**
     * [save__image_from_url description]
     * @param  [type] $image_url [image source url]
     * @return [type]            [attachement id]
     */
    public function save__image_from_url( $image_url )
    {
        $pathinfo         = pathinfo($image_url);
        $extension        = isset($pathinfo['extension']) ? ( strpos($pathinfo['extension'], "?") ? substr($pathinfo['extension'], 0, strpos($pathinfo['extension'], "?")) : $pathinfo['extension'] ) : 'jpeg'; 
        $image_name       = $pathinfo['filename'] .'.'. $extension ;
        $upload_dir       = wp_upload_dir(); // Set upload folder
        $image_data       = file_get_contents($image_url); // Get image data
        $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
        $filename         = basename( $unique_file_name ); // Create image file name

        // Check folder permission and define file location
        if( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        // Create the image  file on the server
        file_put_contents( $file, $image_data );

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file, get_the_ID() );

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id; 
    }

}

