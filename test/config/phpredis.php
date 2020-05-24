<?php

define('ABSPATH', '/wp/');
define('WP_REDIS_PLUGIN_DIR', ABSPATH . 'wp-content/mu-plugins/wp-redis');

define('WP_CACHE_KEY_SALT', 'oc:');
define('WP_CACHE_VERSION_KEY_SALT', 'ocv:');
define('TAROSKY_WP_REDIS_PATCH_DEBUG', false);
define('TAROSKY_WP_REDIS_PATCH_LUA_DIR', getcwd() . '/lua');

require_once '/code/object-cache.php';
require_once WP_REDIS_PLUGIN_DIR . '/wp-redis.php';

foreach (glob('/code/test/helper/{,*/}*.php', GLOB_BRACE) as $file) {
    require $file;
}

// Stub function
function apply_filters($name, $value, ...$params) {
    return $value;
}

function is_multisite() {
    return false;
}

function rand_str($len = 32) {
    return substr(md5(uniqid(rand())), 0, $len);
}

function get_current_blog_id() {
    return 269;
}
