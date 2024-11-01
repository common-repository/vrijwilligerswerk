<?php
/*
Plugin Name: Vrijwilligersvacaturebank_nl
Plugin URI: http://vrijwilligersvacaturebank.nl/
Description: Publish Landing Pages to your Wordpress Domain.
Version: 1.0.0
Author: vrijwilligersvacaturebank.nl
Author URI: http://vrijwilligersvacaturebank.nl/
License: GPLv2
*/
require_once dirname(__FILE__) . '/EVConfig.php';
require_once dirname(__FILE__) . '/EVTemplate.php';
require_once dirname(__FILE__) . '/EVCompatibility.php';
require_once dirname(__FILE__) . '/EVVrijwilligersvacaturebank_nl.php';
require_once dirname(__FILE__) . '/EVUtil.php';
require_once dirname(__FILE__) . '/vrijwilligersvacaturebank_nl-admin.php';
/* Add action to add config menu in admin */
add_action('admin_menu', 'vrijwilligersvacaturebank_nl_menu');

//To remove the config detail upon deactivation
register_deactivation_hook( __FILE__, 'vrijwilligersvacaturebank_nl_deactivate' );


add_action('init', function() {
//To get the domain detail
  $domain = EVConfig::domain();
  $route = get_option('consumerroute' );
  $disablefront = get_option('disablefront' );
  $ps_domain = EVConfig::page_server_domain();
  $http_method = EVUtil::array_fetch($_SERVER, 'REQUEST_METHOD');
  $referer = EVUtil::array_fetch($_SERVER, 'HTTP_REFERER');
  $user_agent = EVUtil::array_fetch($_SERVER, 'HTTP_USER_AGENT');
  $current_path = EVUtil::array_fetch($_SERVER, 'REQUEST_URI');
 
  if (version_compare(phpversion(), '5.4.0', '<')) {
     if(session_id() == '') {
        session_start();
     }
 }
 else
 {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
 }
	$query_string = explode('?',$current_path);
  $path = explode('/',$query_string[0]);
 
  $key  = array_search($route, $path);
  $page = array_slice($path,($key+1));
  $path = array_slice($path,0,$key); 
  if(count($page)<=0)
	$page = array('jobs');
  $page = implode('/',$page);
  if(!isset($path))
	  $path = array();
	$path = implode('/',$path).'/';
	
	if (isset($_GET['session'])) 
	{
		$_SESSION['sess'] = $_GET['session'];
		header("Location: ".$path.$route);
	}
  if($key && empty($disablefront))
  {
	  
	$apiurl = EVConfig::api_url();
	$req_headers = $referer == null ? array('Host: ' . $domain) : array('Referer: ' . $referer, 'Host: ' . $domain);
	//Authrize keys and Generate authrized token. Token will be generated at everytime on page load.
	$token = EVVrijwilligersvacaturebank_nl::authorize_keys($apiurl,"POST", $user_agent,$req_headers);
	if(!empty($token))
	{	
		//Download the page from api and Publish it on front end
		EVVrijwilligersvacaturebank_nl::publish($apiurl,$token,$path.$route,$page, $user_agent,$req_headers);
		
	}
  }
 
  
});

add_action('admin_init', function() 
	{
		
		global $current_user;
		$user_id = $current_user->ID;
		if (isset($_GET['ignore-notice'])) {
		add_user_meta($user_id, 'vrijwilligersvacaturebank_nl_plugin_notice_ignore', 'true', true);
		}
		//Show admin notice to configure plugin
		$route = get_option('consumerroute' );
		if(empty($route))
		add_action( 'admin_notices', 'vrijwilligersvacaturebank_nl_admin_notices' );
	});
?>
