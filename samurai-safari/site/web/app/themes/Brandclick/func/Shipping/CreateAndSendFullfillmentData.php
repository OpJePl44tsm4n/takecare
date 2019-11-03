<?php 
namespace Brandclick\Shipping;
use Brandclick\Brandclick;
use phpseclib\Net\SFTP;

class CreateAndSendFullfillmentData {

	public function __construct()
  	{	
        // Actions
        add_action( 'template_redirect',                            array( $this, 'action__create_open_orders_page' ));
        add_action( 'init',                                         array( $this, 'action__register_open_orders_embed_url' ), 1);
        add_action( 'wp_ajax_nopriv_update_order',                 array( $this, 'action__generate_order_label' ));
        add_action( 'wp_ajax_update_order',                         array( $this, 'action__generate_order_label' ));

        //Filters 
        add_filter( 'query_vars',                                   array( $this, 'filter__register_open_orders_embed_query_var' ));

    }    

    /**
     * Create the order csv file 
     *
     * @param int $order_id
     * @param string $file_name
     * @param object $data
     */
    public function callback__create_order_csv($order_id, $file_name, $data)
    {
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/shipping_export';

        //Create the upload Folder if it doesn't exist
        if (! is_dir($upload_dir)) {
           mkdir( $upload_dir, 0700 );
        }

        $file_location = $upload_dir . '/' . $file_name . '.txt';
        $csv_file = fopen($file_location, "w") or die("Unable to open file!");

        // Fill the data 
        foreach ($data as $fields) {
        if( is_object($fields) )
            $fields = (array) $fields;
            fwrite($csv_file, implode(";", $fields));
        }
        fclose($csv_file);
        
        $file = [
            'name' => $file_name,
            'location' => $file_location
        ];

        return $file;
    }

    /**
     * Save the order on the external server 
     *
     * @param int $order_id
     * @param string $file_name
     * @param string $file_location
     */
    public function callback__upload_order_csv( $order_files ) { 

        global $woocommerce;
        $order_id = $order_files['order_id'];
        $order = new \WC_Order($order_id);
        $test_folder = 'test-brandclick';
        $succes = false; 

        if(!SHIPPING_SERVER || !SHIPPING_USER || !SHIPPING_PASS || !SHIPPING_PORT) {
            update_post_meta($order_id, 'bc_shipping_status', 'error');
            return __("Shipping: no FTP credentials provided", Brandclick::THEME_SLUG);
        }


        // set up basic connection
        $conn_id = ftp_connect(SHIPPING_SERVER);
        // login with username and password
        $login_result = ftp_login($conn_id, SHIPPING_USER, SHIPPING_PASS);
        // turn passive mode on 
        ftp_pasv($conn_id, false);  

        if( get_option('mailtrap_toggle') ){

            // check if test folder exists
            if( @ftp_chdir( $conn_id, $test_folder ) ) { 
                // we know the folder exists since we entered it, now go back up one
                ftp_cdup( $conn_id ); 
            } else { 
                // no folder, we create one with the correct rights 
                ftp_mkdir($conn_id, $test_folder);
                ftp_chmod($conn_id, 0700, $test_folder);
            }
            
            // set our current folder to the test folder 
            @ftp_chdir( $conn_id, $test_folder );
        }

        foreach ($order_files['files'] as $key => $value) {
            $file_name = $value['name']; 
            $file_location = $value['location']; 
            $remote_file =  $file_name . '.txt';

            // upload a file
            if (ftp_put($conn_id, $remote_file, $file_location, FTP_ASCII)) {
                $succes = true; 
                $order->add_order_note( __("Shipping: successfully uploaded $remote_file", Brandclick::THEME_SLUG) );
                $order->save();
            } else {
                $order->add_order_note( __("Shipping: There was a problem while uploading $remote_file", Brandclick::THEME_SLUG) );
                $order->save();
            }
        }

        // close the connection
        ftp_close($conn_id);

        // // set up basic connection
        // $sftp = new SFTP(SHIPPING_SERVER, SHIPPING_PORT);

        // // login with username and password
        // if (!$sftp->login(SHIPPING_USER, SHIPPING_PASS)) {
        //     $order->add_order_note( __("Shipping: error no SFTP connection", Brandclick::THEME_SLUG) );
        //     $order->save();
        //     return 'error';
        // } else {

        //     if( get_option('mailtrap_toggle') ){
    
        //         // check if test folder exists
        //         if( $sftp->chdir( $test_folder ) ) { 
        //             // we know the folder exists since we entered it, now go back up one
        //             $sftp->chdir('..'); 
        //         } else { 
        //             // no folder, we create one with the correct rights 
        //             $sftp->mkdir( $test_folder );
        //             $sftp->chmod( 0700, $test_folder );
        //         }
                
        //         // set our current folder to the test folder 
        //         $sftp->chdir( $test_folder );
        //     }

        //     foreach ($order_files['files'] as $key => $value) {

        //         $file_name = $value['name']; 
        //         $file_location = $value['location']; 
        //         $remote_file =  $file_name . '.txt';

        //         // upload a file
        //         if ($sftp->put( $remote_file, $file_location, SFTP::SOURCE_LOCAL_FILE)) {
        //             $succes = true; 
        //             $order->add_order_note( __("Shipping: successfully uploaded $remote_file", Brandclick::THEME_SLUG) );
        //             $order->save();
        //         } else {
        //             $order->add_order_note( __("Shipping: There was a problem while uploading $remote_file", Brandclick::THEME_SLUG) );
        //             $order->save();
        //         }
        //     } 

        //     $sftp->disconnect();
        // }

       

        // succes is only true succes without errors 
        if($succes) {
            update_post_meta($order_id, 'bc_shipping_status', 'succes');
            return 'succes'; 
        } else {
            update_post_meta($order_id, 'bc_shipping_status', 'error');
            return 'error'; 
        }

    }


    /**
    *   Create order object after payment 
    */
    public function callback__create_order_file( $order_id )
    {           
        if(!function_exists('wc_get_order')){
            return; 
        }

        $order = wc_get_order( $order_id );
        if(!$order) {
            return;
        }

        $debug = [
            'generated_file' => false,
            'uploaded_file' => false 
        ];
        $order_data = $order->get_data();
        
        // BILLING DATA
        $billing_first_name = $order_data['billing']['first_name'];
        $billing_last_name = $order_data['billing']['last_name'];
        $billing_address_1 = str_replace(array("\r", "\n"), '', $order_data['billing']['address_1']);
        $billing_address_2 = str_replace(array("\r", "\n"), '', $order_data['billing']['address_2']);
        $billing_city = $order_data['billing']['city'];
        $billing_postcode = $order_data['billing']['postcode'];
        $billing_country = $order_data['billing']['country'];
        $billing_email = $order_data['billing']['email'];
        $billing_phone = $order_data['billing']['phone'];

        // SHIPPING DATA
        $shipping_first_name = $order_data['shipping']['first_name'] ?: $billing_first_name;
        $shipping_last_name = $order_data['shipping']['last_name'] ?: $billing_last_name;
        $full_name = $shipping_first_name . ' ' . $shipping_last_name; 
        $shipping_address_1 = str_replace(array("\r", "\n"), '', $order_data['shipping']['address_1']) ?: $billing_address_1;
        $shipping_address_2 = str_replace(array("\r", "\n"), '', $order_data['shipping']['address_2']) ?: $billing_address_2;
        $full_address = $shipping_address_1 . ' ' . $shipping_address_2;
        $shipping_city = $order_data['shipping']['city'] ?: $billing_city;
        $shipping_postcode = $order_data['shipping']['postcode'] ?: $billing_postcode;
        $shipping_country = $order_data['shipping']['country'] ?: $billing_country;
        
        $package_count = 1;
        $package_type = 'NP';
        $package_id = 'Palais de l`eau order ' . $order_id;

        // check if date (w) is thursday > 17:00 Or is friday > 15:00 
        if( (date("w") == 4 && date("Hi") > "1700") || (date("w") == 5 && date("Hi") < "1500") ){
            $package_type = 'NP, SAT';
        }
        
        $order_csv_data = [
            [   
                $package_type,
                $full_name,
                $full_address,
                $shipping_city,
                $shipping_country,
                $shipping_postcode,
                $billing_phone,
                $billing_email,
                $package_id,
                $package_count
            ]    
        ];

        $order_files = [
            'order_id' => $order_id,
            'files' => []
        ];
        $order_title = 'palais_Order_' . $order_id ;
        $order_files['files'][] = self::callback__create_order_csv($order_id, $order_title, $order_csv_data);
        
        if(!empty($order_files)){
            $debug['generated_file'] = true;

            $upload = self::callback__upload_order_csv($order_files); 
            if( $upload === 'succes' ){
                $debug['uploaded_file'] = true;
            }    
        }

        return $debug; 
        
    }

   

    /**
     * Send the data on admin created orders 
     *
     * @param obj $column
    */
    public function action__get_admin_oder_details( $post_id ) 
    {   
        if( (!is_admin() ) ){
            return;
        }

        self::action__get_order_details_by_id($post_id);
    }

    /**
     * [filter__register_open_orders_embed_url description]
     * @return [HTML] [Creates an embed page with open orders]
     */
    public function action__register_open_orders_embed_url() 
    {   
        add_rewrite_tag('%order_embed%', '([^&]+)');
        add_rewrite_rule(
            '^open-orders-embed',
            'index.php?order_embed=1',
            'top'
        );
    }

    /**
     * [filter__register_open_orders_embed_query_var add the bc_order_embed query variable]
     * @return [array] [Returns the query_vars obj]
     */
    public function filter__register_open_orders_embed_query_var( $query_vars ) 
    {  
        $query_vars[] = 'order_embed';
        return $query_vars;
    }
    

    /**
     * [action__create_open_orders_page create the open orders page]
     * @return [HTML] [Returns the open orders embed page]
     */
    public function action__create_open_orders_page() 
    {   
        $allowed_ips = get_option('site_redirect_allowed_ips');
        $allowed_ips_array = $allowed_ips ? explode(",",$allowed_ips) : []; 
        $custom = intval( get_query_var( 'order_embed' ) );
    
        if ( $custom && in_array($_SERVER['REMOTE_ADDR'], $allowed_ips_array) ) {  
            include(locate_template('func/Templates/OpenOrdersEmbed.php'));
            die();
        } elseif( $custom ) {
           wp_redirect(home_url());
           exit();
        }
    }

     /**
     * [action__generate_order_label generate the orders label]
     * @return [HTML] [Returns the open orders embed page]
     */
    public function action__generate_order_label() 
    {   
        $order_status = [
            'type' => $_POST['type'],
            'status' => 'processing',
            'debug' => false,
        ];
    

        if( !isset($_POST['type']) && !isset($_POST['value']) ) { 
            $order_status['debug'] = 'Stop messing with me, it\'s my day off!';
            echo json_encode($order_status); 
            die();
        }

        if ( $_POST['type'] === 'generate_label' && isset($_POST['value']) ) {
            
            $order_file = self::callback__create_order_file($_POST['value']);
            if($order_file['uploaded_file']){
                $order_status['status'] = 'File uploaded';
            } else {
                if(!$order_file['generated_file']){
                    $order_status['debug'] = 'Something is wrong, could not create file!';
                } else {
                    $order_status['debug'] = 'Something is wrong, could not upload file!'; 
                }
                
            }
        } 

        if ( $_POST['type'] === 'complete_order' &&  isset($_POST['value'])) {
            $order = wc_get_order( $_POST['value'] );
            if($order) {
                $order->update_status( 'completed' );
                $order_status['status'] = 'completed';
            } else {
                $order_status['debug'] = 'not a valid order ID';
            }

        }

        echo json_encode($order_status); 
        die();
    }
    
    
   
}