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

}

