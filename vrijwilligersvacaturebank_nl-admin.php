<?php
/**
 * vrijwilligersvacaturebank_nl_menu
 *
 * Add menu in admin 
 *
 */
function vrijwilligersvacaturebank_nl_menu() {
	$hook_suffix = add_menu_page( 'Vacatures Options', 'Vacatures', 'manage_options', 'vrijwilligersvacaturebank_nl_option_menu', 'vrijwilligersvacaturebank_nl_options' );
	
	add_action( 'load-' . $hook_suffix , 'vrijwilligersvacaturebank_nl_load_function' );
}

/**
 * vrijwilligersvacaturebank_nl_load_function
 *
 * Remove configure notice from admin 
 *
 */
function vrijwilligersvacaturebank_nl_load_function() {
	remove_action( 'admin_notices', 'vrijwilligersvacaturebank_nl_admin_notices' );
}
/**
 * vrijwilligersvacaturebank_nl_admin_notices
 *
 * Configure notice details for admin 
 *
 */
function vrijwilligersvacaturebank_nl_admin_notices() {
	global $current_user;
	
	$user_id = $current_user->ID;
	
	if (!get_user_meta($user_id, 'vrijwilligersvacaturebank_nl_plugin_notice_ignore')) 
	{
		echo "<div id='notice' class='notice updated fade'><p>Vrijwilligersvacaturebank_nl Plugin is not configured yet. Please do it now. <a href='?ignore-notice=1' style='float: right;'>Dismiss</a></p></div>\n";
	}
}

/**
 * vrijwilligersvacaturebank_nl_options
 *
 * Display and Save configuration option  
 *
 */
function vrijwilligersvacaturebank_nl_options() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }  
   extract($_POST);
    // See if the user has posted us some information
    // If they did, this submit field will be set
    if( !empty($consumerkey) && !empty($consumersecret)  && !empty($consumerroute) && !empty($submit)) 
	{
	$ps_domain = EVConfig::page_server_domain();
	$http_method = EVUtil::array_fetch($_SERVER, 'REQUEST_METHOD');
	$referer = EVUtil::array_fetch($_SERVER, 'HTTP_REFERER');
	$user_agent = EVUtil::array_fetch($_SERVER, 'HTTP_USER_AGENT');
	$current_path = EVUtil::array_fetch($_SERVER, 'REQUEST_URI');
	$apiurl = EVConfig::api_url();
	$req_headers = $referer == null ? array('Host: ' . $domain) : array('Referer: ' . $referer, 'Host: ' . $domain);
	$token = EVVrijwilligersvacaturebank_nl::authorize_keys($apiurl,"POST", $user_agent,$req_headers,$consumerkey,$consumersecret);
	
		if(!empty($token))
		{
			update_option( 'consumerkey', $consumerkey );
			update_option( 'consumersecret', $consumersecret );
			update_option( 'consumerroute', $consumerroute );
			update_option( 'disablefront', $disablefront );
			
			// Put a "settings saved" message on the screen
			echo EVTemplate::render('res', array());
		}
		else
		{
			echo EVTemplate::render('error', array());
		}
    }
	elseif(!empty($reset))
	{
		update_option( 'consumerkey', '' );
		update_option( 'consumersecret', '' );
		update_option( 'consumerroute', '' );
		update_option( 'disablefront', '' );
		  // Read in existing option value from database
		$consumerkey = get_option('consumerkey' );
		$consumersecret = get_option('consumersecret');
		$consumerroute = get_option('consumerroute' );
		$disablefront = get_option('disablefront' );
	}
	else
	{
		  // Read in existing option value from database
		$consumerkey = get_option('consumerkey' );
		$consumersecret = get_option('consumersecret');
		$consumerroute = get_option('consumerroute' );
		$disablefront = get_option('disablefront' );
	}
	$apiurl = EVConfig::api_url();
    // Now display the settings editing screen
echo EVTemplate::render('options', array('consumerkey' => $consumerkey,'consumersecret' => $consumersecret,'consumerroute' => $consumerroute,'apiurl'=> $apiurl,'disablefront'=>$disablefront));
 
}
/**
 * vrijwilligersvacaturebank_nl_deactivate
 *
 * Delete configuration option from database upon deactivation
 *
 */
function vrijwilligersvacaturebank_nl_deactivate()
{
	delete_option( 'consumerkey');
	delete_option( 'consumersecret');
	delete_option( 'consumerroute');
	delete_option( 'disablefront');
}

?>