<?php 
namespace Greylabel\Tracking;
use Greylabel\Greylabel;

class ServerSideAnalytics {

	public static $ga_id;

	public static function check_ua() {
		if (get_option('google_analytics_id')) { 
			self::$ga_id = get_option("google_analytics_id") ?: false;
			return self::$ga_id; 
		} else {
			return false; 
		}
	}


	//Parse the GA Cookie
	public static function gaParseCookie() {
		if (isset($_COOKIE['_ga'])) {
			list($version, $domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"], 4);
			$contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . '.' . $cid2);
			$cid = $contents['cid'];
		} else {
			$cid = self::gaGenerateUUID();
		}
		return $cid;
	}

	//Generate UUID
	//Special thanks to stumiller.me for this formula.
	public static function gaGenerateUUID() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	//Send Data to Google Analytics
	public static function gaSendData($data) {
		$getString = 'https://www.google-analytics.com/collect?';
		$getString .= http_build_query($data);
		$result = wp_remote_get($getString);
		return $result;
	}

	// Send Transaction
	public static function gaPrepareTransaction($order_data)
	{
		if (!self::check_ua()) {
			return false;
		}
		$data = array(
			'v' => 1,
			'tid' => self::$ga_id,
			'cid' => self::gaParseCookie(),
			't' => 'pageview',
			'ec' => 'Ecommerce', 
			'ea' => 'Online Purchase',
			'dh' => $_SERVER['HTTP_HOST'], 
			'dp' => '/order-received', //Page 
			'dt' => 'Order%20Received' // Title.
		);

		$data = array_merge($data, $order_data);
		
		return $data;	
	}

	//Prepare Pageview
	public static function gaPreparePageview($hostname=null, $page=null, $title=null) 
	{
		if (!self::check_ua()) {
			return false;
		}

		$data = array(
			'v' => 1,
			'tid' => self::$ga_id,
			'cid' => self::gaParseCookie(),
			't' => 'pageview',
			'dh' => $_SERVER['HTTP_HOST'], 
			'dp' => $page, //Page "/something"
			'dt' => $title //Title
		);
		self::gaSendData($data);
	}

	//Prepare Event
	public static function gaPrepareEvent($category=null, $action=null, $label=null) 
	{
		if (!self::check_ua()) {
			return false;
		}

		$data = array(
			'v' => 1,
			'tid' => self::$ga_id, 
			'cid' => self::gaParseCookie(),
			't' => 'event',
			'ec' => $category, //Category (Required)
			'ea' => $action, //Action (Required)
			'el' => $label //Label
		);
		self::gaSendData($data);
	}
}