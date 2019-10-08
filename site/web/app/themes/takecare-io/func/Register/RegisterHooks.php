<?php 
namespace Greylabel\Register;
use Greylabel\Greylabel;

class RegisterHooks {

	/**
    *   Custom body function to hook functions to 
    */
    public function wp_body() 
    {
        do_action('Greylabel_after_body_tag');
    }

}

