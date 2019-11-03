<?php 
namespace Brandclick\Register;
use Brandclick\Brandclick;

class RegisterHooks {

	/**
    *   Custom body function to hook functions to 
    */
    public function wp_body() 
    {
        do_action('brandclick_after_body_tag');
    }

}

