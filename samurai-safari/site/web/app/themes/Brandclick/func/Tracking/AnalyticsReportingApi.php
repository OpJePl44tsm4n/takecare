<?php 
/**
 * Analytics Reporting APi v4
 * Docs:   -    
 */

namespace Brandclick\Tracking;
use Brandclick\Brandclick;

class AnalyticsReportingApi {

	protected $view_id;
    protected $key_json;
    public $page_types = [];  

  	public function __construct()
  	{
  		$this->view_id = get_option('google_view_container_id') ?: false; 
  		$this->page_types = [ 'magazine', 'product'];

    	if ( ! wp_next_scheduled( 'analytics_page_views' ) && $this->view_id ) {
            wp_schedule_event( time(), 'daily', 'analytics_page_views' ); //daily
        }

    	// Actions
    	add_action( 'analytics_page_views',                      array( $this, 'action__get_analytics_report') );
        add_action( 'admin_init',                      			 array( $this, 'action__register_analytics_settings') );

        // run this function directly if de option is not set 
        if( ! get_option('ga_top_page_views') || isset($_GET['debug_analytics']) ) { 
        	foreach ($this->page_types as $type ) {
        		self::action__get_analytics_report($type); 
        	}
            
        } 
  	}


  	/**
	* 
	*/
	public function action__register_analytics_settings()
	{	
		add_settings_section(
            'google_analytics_section',        
            'Google Tracking Options',         
            function () {
            	$tool_link = esc_url( 'https://console.developers.google.com/start/api?id=analyticsreporting.googleapis.com&credential=client_key' );
				$format_tool_link = '<a href="'. $tool_link .'" target="_blank">setup tool</a>'; 
				$ga_link = esc_url( 'https://analytics.google.com/analytics/web/' );
				$format_ga_link = '<a href="'. $ga_link .'" target="_blank">Analytics</a>'; 

                echo '<i>'.  sprintf( __( 'Use the %s to create a JSON key and upload the file as \'google_config_key.json\' to the root <b>(%s)</b> of the site.</br>Make sure to add the service account email as a user with viewing rights to %s', Brandclick::THEME_SLUG  ),
                	$format_tool_link,
                	trailingslashit( dirname( dirname( ABSPATH ) ) ), 
                	$format_ga_link ) .
                '</i>' ;

                $top_views = get_option('ga_top_page_views'); 
                if($top_views): ?>
		
					</br></br>
					<h2 style="margin-bottom: 0;"><?php _e( 'Most viewed posts', Brandclick::THEME_SLUG  ) ?></h2>
					<small><i>(<?php echo sprintf( __( 'last import: %s', Brandclick::THEME_SLUG  ), $top_views['last_import']) ?>)</i></small>
					</br></br>

					<?php foreach ($this->page_types as $type ): ?>
						<table style="text-align:left;display:inline-block;padding-right: 4rem;float:left;">
						  	<tr>
							    <th><?php echo $type ?></th>
							    <th style="padding-left: 20px">Views</th> 
						  	</tr>

			               <?php foreach ($top_views['debug'][$type] as $key => $value) { ?>
								<tr>
								    <td><a href="<?php echo $value['permalink']; ?>"><?php echo $value['title']; ?></a></td>
								    <td style="padding-left: 20px"><?php echo $value['pageviews']; ?></td> 
			  					</tr>
			                <?php } ?>

						</table>
					<?php endforeach; ?>	
					<div style="clear: both;"></div>
					<?php if ( isset($_GET['debug_analytics']) ) { ?>
						<pre>
							<?php var_dump($top_views); ?>
						</pre>
				    <?php } ?>
					</br></br>

				<?php endif;  
			},
            'brandclick_options'                   
        );

		add_settings_field( 
            'google_tag_manager_id',
            __( 'Google Tagmanager ID:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'google_analytics_section',
            array(
                'id'            => 'google_tag_manager_id',
                'placeholder'   => 'GTM-ABCDEFG3'
            )
        );

        add_settings_field( 
            'google_analytics_id',
            __( 'Google Analytics ID:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'google_analytics_section',
            array(
                'id'            => 'google_analytics_id',
                'placeholder'   => 'UA-123456789-1'
            )
        );

        add_settings_field( 
            'google_view_container_id',
            __( 'Google Analytics View ID:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'google_analytics_section',
            array(
                'id'            => 'google_view_container_id',
                'placeholder'   => '123456789'
            )
        );

        add_settings_field( 
            'google_api_key',
            __( 'Google Api Key:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'google_analytics_section',
            array(
                'id'            => 'google_api_key',
                'placeholder'   => 'AIzaDFHsdfeBMEgePkhTOM-3-oO5dTapXBrlx8'
            )
        );
     
        // Google settings
        register_setting( 'brandclick_options', 'google_analytics_id' ); 
        register_setting( 'brandclick_options', 'google_view_container_id' );   
        register_setting( 'brandclick_options', 'google_tag_manager_id' ); 
        register_setting( 'brandclick_options', 'google_api_key' );
	}



	/**
	* Initializes an Analytics Reporting API V4 service object.
	*
	* @return An authorized Analytics Reporting API V4 service object.
	*/
	public function initializeAnalytics()
	{
		// Use the developers console and download your service account
		// credentials in JSON format. Place them in this directory or
		// change the key file location if necessary.
		$KEY_FILE_LOCATION = trailingslashit( dirname( dirname( ABSPATH ) ) ) . 'google_config_key.json';

		if(!file_exists($KEY_FILE_LOCATION)) {
			return false; 
		}

		// Create and configure a new client object.
		$client = new \Google_Client();
		$client->setApplicationName("Serverside Analytics Reporting");
		$client->setAuthConfig($KEY_FILE_LOCATION);
		$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
		$analytics = new \Google_Service_AnalyticsReporting($client);

		return $analytics;
	}


	/**
	* Queries the Analytics Reporting API V4.
	*
	* @param service An authorized Analytics Reporting API V4 service object.
	* @return The Analytics Reporting API V4 response.
	*/
	public function action__get_analytics_report($type) 
	{	
		$analytics = self::initializeAnalytics(); 

		if(!$analytics || !$this->view_id ) {
			return; 
		}

		// Create the DateRange object.
		$dateRange = new \Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate("30daysAgo");
		$dateRange->setEndDate("yesterday");

		// Create the Metrics object.
		$sessions = new \Google_Service_AnalyticsReporting_Metric();
		$sessions->setExpression('ga:pageviews');
		$sessions->setAlias("pageviews");

		//Create the Dimensions object.
		$pagePath = new \Google_Service_AnalyticsReporting_Dimension();
		$pagePath->setName("ga:pagePath");

		//Create the Dimensions object.
		$pageTitle = new \Google_Service_AnalyticsReporting_Dimension();
		$pageTitle->setName("ga:pageTitle");

		// Create the ReportRequest object.
		$request = new \Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId( $this->view_id );
		$request->setPageSize(10);
		$request->setOrderBys(['fieldName' => 'ga:pageviews', 'sortOrder' => 'DESCENDING']);
		$filter_string = 'ga:pagePath=@/'.$type.'/;ga:pagePath!=/'.$type.'/';
		$request->setFiltersExpression($filter_string);
		$request->setDateRanges($dateRange);
		$request->setDimensions(array($pagePath));
		$request->setMetrics(array($sessions));

		$body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests( array( $request) );

		$data = $analytics->reports->batchGet( $body );

		// return the data as an array so we can use it  
		$analyticsData = self::callback__return_analytics_data_object($data);

		// create the wp option holding the top viewed pages 
		self::callback__create_ga_top_page_views_option( $type, $analyticsData );

		
	}

	/**
	 * Parses and prints the Analytics Reporting API V4 response.
	 *
	 * @param An Analytics Reporting API V4 response.
	 */
	
	public function callback__return_analytics_data_object($reports) 
	{
		for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
		    $report = $reports[ $reportIndex ];
		    $header = $report->getColumnHeader();
		    $dimensionHeaders = $header->getDimensions() ?: [];
		    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
		    $rows = $report->getData()->getRows();
		    $data = [];

		    for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
				$row = $rows[ $rowIndex ];
				$dimensions = $row->getDimensions() ?: [];
				$metrics = $row->getMetrics();
				for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
					$data[$rowIndex][$dimensionHeaders[$i]] = $dimensions[$i];
				}

				for ($j = 0; $j < count($metrics); $j++) {
					$values = $metrics[$j]->getValues();
					for ($k = 0; $k < count($values); $k++) {
						$entry = $metricHeaders[$k];
						$data[$rowIndex][$entry->getName()] = $values[$k];
					}
				}
		    }

		    return $data; 
		
  		}
	}


	/**
	 * Uses de GA object en returns the proper page objects & update option ga_top_page_views
	 *
	 * @param the filtered data array handeled by callback__return_analytics_data_object
	 * @return updates ga_top_page_views option  
		[
			'top_post_ids' => [1, 2, 3, 4],
			'last_import' => date,
			'debug' => [
				[0] => [
					'post_id' => 1
					'title' => 'title'
					'original_url' => 'link saved in google analytics'
					'permalink' =>	'link'
					'pageviews' => 1
				]
			]
		];
	 */
	
	public function callback__create_ga_top_page_views_option($type, $analyticsData) 
	{	
		$postList = get_option('ga_top_page_views') ?: [];
		$type_page = ($type == 'product') ? $type : 'post';
		$postList['top_'.$type_page.'_ids'] = [];
		$postList['top_'.$type_page.'_count'] = [];

		foreach ($analyticsData as $key => $data) {
			$url = $data['ga:pagePath']; 
			$url = strpos($url, "?") ? substr($url, 0, strpos($url, "?")) : $url; // remove url parameters 
		 	$page = get_page_by_path( trailingslashit( '/' . basename($url) ),OBJECT, $type_page); // get the last part of the url and try to get a post
		
		 	if($page){
		 		$postList['top_'.$type_page.'_ids'][] = $page->ID;
		 		$postList['top_'.$type_page.'_count'][$page->ID] = isset($postList['top_'.$type_page.'_count'][$page->ID]) ? $postList['top_'.$type_page.'_count'][$page->ID] + $data['pageviews'] : (int)$data['pageviews'];
		 		$postList['last_import'] = date('d/m/Y H:i');
		 		$postList['debug'][$type][$key]['post_id'] = $page->ID;
		 		$postList['debug'][$type][$key]['title'] = $page->post_title;
		 		$postList['debug'][$type][$key]['original_url'] = $url;
		 		$postList['debug'][$type][$key]['permalink'] = get_permalink($page->ID);
				$postList['debug'][$type][$key]['pageviews'] = $data['pageviews'];
		 	}
		}
		
		if(!empty($postList)){
			update_option('ga_top_page_views', $postList);
		}	
	}	

}
