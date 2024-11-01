<?php
//Some Utility functions
class EVUtil {

  public static function array_select_by_key($input, $keep) {
    return array_intersect_key($input, array_flip($keep));
  }

  public static function array_fetch($array, $index, $default = null) {
    return isset($array[$index]) ? $array[$index] : $default;
  }

  public static function time_ago($timestamp) {
    $now = new DateTime('now');
    $from = new DateTime();
    $from->setTimestamp($timestamp);
    $diff = date_diff($now, $from);

    if($diff->y > 0) {
      $message = $diff->y . ' year'. ($diff->y > 1 ? 's' : '');
    }
    else if($diff->m > 0) {
      $message = $diff->m . ' month'. ($diff->m > 1 ? 's' : '');
    }
    else if($diff->d > 0) {
      $message = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
    }
    else if($diff->h > 0) {
      $message = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
    }
    else if($diff->i > 0) {
      $message = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    }
    else if($diff->s > 0) {
      $message = $diff->s . ' second' . ($diff->s > 1? 's' : '');
    }
    else {
      $message = 'a moment';
    }

    return $message . ' ago';
  }

  public static function clear_flash() {
    foreach($_COOKIE as $cookie_name => $value) {
      if(strpos($cookie_name, 'ev-flash-') === 0) {
        setcookie($cookie_name, '', time() - 60);
      }
    }
  }

  public static function get_flash($cookie_name, $default = null) {
    return EVUtil::array_fetch($_COOKIE, "ev-flash-${cookie_name}", $default);
  }

  public static function set_flash($cookie_name, $value) {
    setcookie("ev-flash-${cookie_name}", $value, time() + 60);
  }

  public static function get_lock() {
    global $wpdb;

    try {
      $lock = $wpdb->get_var('select coalesce(get_lock("' . EVConfig::EV_LOCK_NAME . '",0), 0);');

      return (bool) $lock;
    }
    catch (Exception $e) {
      // ensure backward compatibility on failure
      return true;
    }
  }

  public static function release_lock() {
    global $wpdb;

    try {
      $release = $wpdb->get_var('select coalesce(release_lock("' . EVConfig::EV_LOCK_NAME . '"), 0);');

      return (bool) $release;
    }
    catch (Exception $e) {
      // ensure backward compatibility on failure
      return true;
    }
  }

}
?>
