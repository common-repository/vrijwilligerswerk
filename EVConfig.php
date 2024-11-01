<?php

class EVConfig {
	
    # Option keys
  const EV_API_URL_KEY            = 'ev-api-url';
  const EV_PAGE_SERVER_DOMAIN_KEY = 'ev-page-server-domain';
 

  public static function ev_option_keys() {
    # All options, used by EVDiagnostics
    # Arrays are not allowed in class constants, so use a function
    return array(
      EVConfig::EV_API_URL_KEY,
    );
  }

 

  /**
 * default_api_url
 *
 * Return default url of the api
 *
 */
  public static function default_api_url() {
    $url = getenv('EV_API_URL');
	
    return $url ? $url : 'http://api.vvb-staging-nl.eu';
  }
/**
 * api_url
 *
 * check and Return url of the api
 *
 */
  public static function api_url() {
    return get_option(EVConfig::EV_API_URL_KEY, EVConfig::default_api_url());
  }
/**
 * domain
 *
 * Return domain of the website
 *
 */
 
  public static function domain() {
    return parse_url(get_home_url(), PHP_URL_HOST);
  }

  /**
 * default_page_server_domain
 *
 * Return default server domain
 *
 */
  public static function default_page_server_domain() {
    $domain = getenv('EV_PAGE_SERVER_DOMAIN');
    return $domain ? $domain : 'wp.ev.com';
  }
  /**
 * page_server_domain
 *
 * Check and Return server domain
 *
 */
  public static function page_server_domain() {
    return get_option(EVConfig::EV_PAGE_SERVER_DOMAIN_KEY, EVConfig::default_page_server_domain());
  }
  
  /**
 * domain_with_port
 *
 * Return website domain with port
 *
 */
  public static function domain_with_port() {
    $port = parse_url(get_home_url(), PHP_URL_PORT);
    $host = parse_url(get_home_url(), PHP_URL_HOST);
    if ($port) {
      return $host . ':' . $port;
    } else {
      return $host;
    }
  }



}
?>
