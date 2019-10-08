<?php 
namespace Greylabel\Admin;
use Greylabel\Greylabel;

class CleanUpUserProfiles {

	/**
    *   Remove unnecessary user fields 
    */
    public static function filter__unset_user_fields( $contact_methods ) {
    
        unset($contact_methods['googleplus']);
        unset($contact_methods['twitter']);
        unset($contact_methods['facebook']);

        return $contact_methods;
    }

  

}

