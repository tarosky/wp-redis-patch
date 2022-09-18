<?php

/**
 * Originally created by Pantheon as https://github.com/pantheon-systems/wp-redis
 * Modified by Tarosky INC.
 */

if (!defined('WP_CACHE_KEY_SALT')) {
    define('WP_CACHE_KEY_SALT', '');
}

if (!defined('WP_REDIS_DEFAULT_EXPIRE_SECONDS')) {
    define('WP_REDIS_DEFAULT_EXPIRE_SECONDS', 0);
}

/**
 * Adds data to the cache, if the cache key doesn't already exist.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::add()
 *
 * @param int|string $key The cache key to use for retrieval later
 * @param mixed $data The data to add to the cache store
 * @param string $group The group to add the cache to
 * @param int $expire When the cache data should be expired
 * @return bool False if cache key and group already exist, true on success
 */
function wp_cache_add($key, $data, $group = '', $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS) {
    global $wp_object_cache;

    return $wp_object_cache->add($key, $data, $group, (int) $expire);
}

/**
 * Closes the cache.
 *
 * This function has ceased to do anything since WordPress 2.5. The
 * functionality was removed along with the rest of the persistent cache. This
 * does not mean that plugins can't implement this function when they need to
 * make sure that the cache is cleaned up after WordPress no longer needs it.
 *
 * @return bool Always returns True
 */
function wp_cache_close() {
    return true;
}

/**
 * Decrement numeric cache item's value
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::decr()
 *
 * @param int|string $key The cache key to increment
 * @param int $offset The amount by which to decrement the item's value. Default is 1.
 * @param string $group The group the key is in.
 * @return false|int False on failure, the item's new value on success.
 */
function wp_cache_decr($key, $offset = 1, $group = '') {
    global $wp_object_cache;

    return $wp_object_cache->decr($key, $offset, $group);
}

/**
 * Removes the cache contents matching key and group.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::delete()
 *
 * @param int|string $key What the contents in the cache are called
 * @param string $group Where the cache contents are grouped
 * @return bool True on successful removal, false on failure
 */
function wp_cache_delete($key, $group = '') {
    global $wp_object_cache;

    return $wp_object_cache->delete($key, $group);
}

/**
 * Removes cache contents for a given group.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::delete_group()
 *
 * @param string $group Where the cache contents are grouped
 * @return bool True on successful removal, false on failure
 */
function wp_cache_delete_group($group) {
    global $wp_object_cache;
    return $wp_object_cache->delete_group($group);
}


/**
 * Removes all cache items.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::flush()
 *
 * @return bool False on failure, true on success
 */
function wp_cache_flush() {
    global $wp_object_cache;

    return $wp_object_cache->flush();
}

/**
 * Retrieves the cache contents from the cache by key and group.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::get()
 *
 * @param int|string $key What the contents in the cache are called
 * @param string $group Where the cache contents are grouped
 * @param bool $force Whether to force an update of the local cache from the persistent cache (default is false)
 * @param &bool $found Whether key was found in the cache. Disambiguates a return of false, a storable value.
 * @return bool|mixed False on failure to retrieve contents or the cache contents on success
 */
function wp_cache_get($key, $group = '', $force = false, &$found = null) {
    global $wp_object_cache;

    return $wp_object_cache->get($key, $group, $force, $found);
}

/**
 * Retrieves multiple values from the cache in one call.
 *
 * @see WP_Object_Cache::get_multiple()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param array  $keys  Array of keys under which the cache contents are stored.
 * @param string $group Optional. Where the cache contents are grouped. Default empty.
 * @param bool   $force Optional. Whether to force an update of the local cache
 *                      from the persistent cache. Default false.
 * @return array Array of values organized into groups.
 */
function wp_cache_get_multiple($keys, $group = '', $force = false) {
    global $wp_object_cache;

    return $wp_object_cache->get_multiple($keys, $group, $force);
}

/**
 * Increment numeric cache item's value
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::incr()
 *
 * @param int|string $key The cache key to increment
 * @param int $offset The amount by which to increment the item's value. Default is 1.
 * @param string $group The group the key is in.
 * @return false|int False on failure, the item's new value on success.
 */
function wp_cache_incr($key, $offset = 1, $group = '') {
    global $wp_object_cache;

    return $wp_object_cache->incr($key, $offset, $group);
}

/**
 * Sets up Object Cache Global and assigns it.
 *
 * @global WP_Object_Cache $wp_object_cache WordPress Object Cache
 */
function wp_cache_init() {
    global $wp_object_cache;

    if (!($wp_object_cache instanceof WP_Object_Cache)) {
        $wp_object_cache = new WP_Object_Cache;
    }
}

/**
 * Replaces the contents of the cache with new data.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::replace()
 *
 * @param int|string $key What to call the contents in the cache
 * @param mixed $data The contents to store in the cache
 * @param string $group Where to group the cache contents
 * @param int $expire When to expire the cache contents
 * @return bool False if not exists, true if contents were replaced
 */
function wp_cache_replace($key, $data, $group = '', $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS) {
    global $wp_object_cache;

    return $wp_object_cache->replace($key, $data, $group, (int) $expire);
}

/**
 * Saves the data to the cache.
 *
 * @uses $wp_object_cache Object Cache Class
 * @see WP_Object_Cache::set()
 *
 * @param int|string $key What to call the contents in the cache
 * @param mixed $data The contents to store in the cache
 * @param string $group Where to group the cache contents
 * @param int $expire When to expire the cache contents
 * @return bool False on failure, true on success
 */
function wp_cache_set($key, $data, $group = '', $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS) {
    global $wp_object_cache;

    return $wp_object_cache->set($key, $data, $group, (int) $expire);
}

/**
 * Switch the interal blog id.
 *
 * This changes the blog id used to create keys in blog specific groups.
 *
 * @param int $blog_id Blog ID
 */
function wp_cache_switch_to_blog($blog_id) {
    global $wp_object_cache;

    return $wp_object_cache->switch_to_blog($blog_id);
}

/**
 * Adds a group or set of groups to the list of global groups.
 *
 * @param string|array $groups A group or an array of groups to add
 */
function wp_cache_add_global_groups($groups) {
    global $wp_object_cache;

    return $wp_object_cache->add_global_groups($groups);
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @param string|array $groups A group or an array of groups to add
 */
function wp_cache_add_non_persistent_groups($groups) {
    global $wp_object_cache;

    $wp_object_cache->add_non_persistent_groups($groups);
}

/**
 * Reset internal cache keys and structures. If the cache backend uses global
 * blog or site IDs as part of its cache keys, this function instructs the
 * backend to reset those keys and perform any cleanup since blog or site IDs
 * have changed since cache init.
 *
 * This function is deprecated. Use wp_cache_switch_to_blog() instead of this
 * function when preparing the cache for a blog switch. For clearing the cache
 * during unit tests, consider using wp_cache_init(). wp_cache_init() is not
 * recommended outside of unit tests as the performance penality for using it is
 * high.
 *
 * @deprecated 3.5.0
 */
function wp_cache_reset() {
    _deprecated_function(__FUNCTION__, '3.5');

    global $wp_object_cache;

    return $wp_object_cache->reset();
}

/**
 * Retrieve multiple values from cache.
 *
 * Gets multiple values from cache, including across multiple groups
 *
 * Usage: array( 'group0' => array( 'key0', 'key1', 'key2', ), 'group1' => array( 'key0' ) )
 *
 * Mirrors discussion on Trac: https://core.trac.wordpress.org/ticket/20875#comment:33
 *
 * @param array $groups Array of groups and keys to retrieve
 * @param bool $force Optional. Whether to force an update of the local cache
 *                    from the persistent cache. Default false.
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @return bool|array
 *     Array of cached values, in format:
 *     ['group0' => ['key0' => 'value0', 'key1' => 'value1', 'key2' => 'value2'], 'group1' => ['key0' => 'value0']]
 *     Values not found in cache will be missing along with the corresponding keys.
 */
function wp_cache_tarosky_get_multiple($groups, $force = false) {
    global $wp_object_cache;

    return $wp_object_cache->normalized_get_multiple($groups, $force);
}

if (!defined('WP_CACHE_VERSION_KEY_SALT')) {
    define('WP_CACHE_VERSION_KEY_SALT', 'version:');
}

class WP_Object_Cache {
    /**
     * Holds the cached objects
     *
     * @var array
     * @access private
     */
    var $cache = [];

    /**
     * The amount of times a request was made to Redis
     *
     * @access private
     * @var int
     */
    var $redis_calls = [];

    /**
     * List of global groups
     *
     * @var array
     * @access protected
     */
    var $global_groups = [];

    /**
     * List of non-persistent groups
     *
     * @var array
     * @access protected
     */
    var $non_persistent_groups = [];

    /**
     * The blog prefix to prepend to keys in non-global groups.
     *
     * @var int
     * @access private
     */
    var $blog_prefix;

    /**
     * Whether or not Redis is connected
     *
     * @var bool
     * @access private
     */
    var $is_redis_connected = false;

    /**
     * Whether or not the object cache thinks Redis needs a flush
     *
     * @var bool
     * @access private
     */
    var $do_redis_failback_flush = false;

    /**
     * The last triggered error
     */
    var $last_triggered_error = '';

    /**
     * Sets the list of global groups.
     *
     * @param array $groups List of groups that are global.
     */
    public function add_global_groups($groups) {
        $groups = (array) $groups;

        $groups              = array_fill_keys($groups, true);
        $this->global_groups = array_merge($this->global_groups, $groups);
    }

    /**
     * Sets the list of non-persistent groups.
     *
     * @param array $groups List of groups that are non-persistent.
     */
    public function add_non_persistent_groups($groups) {
        $groups = (array) $groups;

        $groups                      = array_fill_keys($groups, true);
        $this->non_persistent_groups = array_merge($this->non_persistent_groups, $groups);
    }

    /**
     * Reset keys
     *
     * @deprecated 3.5.0
     */
    public function reset() {
        _deprecated_function(__FUNCTION__, '3.5', 'switch_to_blog()');
    }

    /**
     * Switch the interal blog id.
     *
     * This changes the blog id used to create keys in blog specific groups.
     *
     * @param int $blog_id Blog ID
     */
    public function switch_to_blog($blog_id) {
        $blog_id           = (int) $blog_id;
        $this->blog_prefix = $this->multisite ? $blog_id . ':' : '';
    }

    /**
     * Utility function to determine whether a key exists in the cache.
     *
     * @access protected
     */
    protected function _exists($key, $group) {
        if ($this->_isset_internal($key, $group)) {
            return true;
        }

        if (!$this->_should_persist($group)) {
            return false;
        }

        $id = $this->_key($key, $group);
        return $this->_call_redis('exists', $id);
    }

    /**
     * Check whether there's a value in the internal object cache.
     *
     * @param string $key
     * @param string $group
     * @return boolean
     */
    protected function _isset_internal($key, $group) {
        $key = $this->_key($key, $group);
        return array_key_exists($key, $this->cache);
    }

    /**
     * Get a value from the internal object cache
     *
     * @param string $key
     * @param string $group
     * @return mixed
     */
    protected function _get_internal($key, $group) {
        $value = null;
        $key = $this->_key($key, $group);
        if (array_key_exists($key, $this->cache)) {
            $value = $this->cache[$key];
        }
        if (is_object($value)) {
            return clone $value;
        }
        return $value;
    }

    /**
     * Set a value to the internal object cache
     *
     * @param string $key
     * @param string $group
     * @param mixed $value
     */
    protected function _set_internal($key, $group, $value) {
        $key = $this->_key($key, $group);
        $this->cache[$key] = $value;
    }

    /**
     * Unset a value from the internal object cache
     *
     * @param string $key
     * @param string $group
     */
    protected function _unset_internal($key, $group) {
        $key = $this->_key($key, $group);
        if (array_key_exists($key, $this->cache)) {
            unset($this->cache[$key]);
        }
    }

    /**
     * Does this group use persistent storage?
     *
     * @param  string $group Cache group.
     * @return bool        true if the group is persistent, false if not.
     */
    protected function _should_persist($group) {
        return empty($this->non_persistent_groups[$group]);
    }

    /**
     * Wrapper method for connecting to Redis, which lets us retry the connection
     */
    protected function _connect_redis() {
        global $redis_server;

        $check_dependencies = [$this, 'check_client_dependencies'];
        /**
         * Permits alternate dependency check mechanism to be used.
         *
         * @param callable $check_dependencies Callback to execute.
         */
        $check_dependencies = apply_filters('wp_redis_check_client_dependencies_callback', $check_dependencies);
        $dependencies_ok    = call_user_func($check_dependencies);
        if (true !== $dependencies_ok) {
            $this->is_redis_connected    = false;
            $this->missing_redis_message = $dependencies_ok;
            return $this->is_redis_connected;
        }
        $client_parameters = $this->build_client_parameters($redis_server);

        try {
            $client_connection = [$this, 'prepare_client_connection'];
            /**
             * Permits alternate initial client connection mechanism to be used.
             *
             * @param callable $client_connection Callback to execute.
             */
            $client_connection = apply_filters('wp_redis_prepare_client_connection_callback', $client_connection);
            $this->redis       = call_user_func_array($client_connection, [$client_parameters]);
        } catch (Exception $e) {
            $this->_exception_handler($e);
            $this->is_redis_connected = false;
            return $this->is_redis_connected;
        }

        $keys_methods = [
            'auth'     => 'auth',
            'database' => 'select',
        ];

        try {
            $setup_connection = [$this, 'perform_client_connection'];
            /**
             * Permits alternate setup client connection mechanism to be used.
             *
             * @param callable $setup_connection Callback to execute.
             */
            $setup_connection = apply_filters('wp_redis_perform_client_connection_callback', $setup_connection);
            call_user_func_array($setup_connection, [$this->redis, $client_parameters, $keys_methods]);
        } catch (Exception $e) {
            $this->_exception_handler($e);
            $this->is_redis_connected = false;
            return $this->is_redis_connected;
        }

        $this->is_redis_connected = $this->redis->isConnected();
        if (!$this->is_redis_connected) {
            $this->missing_redis_message = 'Warning! WP Redis object cache cannot connect to Redis server.';
        }
        return $this->is_redis_connected;
    }

    /**
     * Are the required dependencies for connecting to Redis available?
     *
     * @return mixed True if the required dependencies are present, string if
     *               not with a message describing the issue.
     */
    public function check_client_dependencies() {
        if (!class_exists('Redis')) {
            return 'Warning! PHPRedis extension is unavailable, which is required by WP Redis object cache.';
        }
        return true;
    }

    /**
     * Builds an array to be passed to a function that will set up the Redis
     * client.
     *
     * @param array $redis_server Parameters used to construct a Redis client.
     * @return array Final parameters to use to contruct a Redis client with
     *               with defaults applied.
     */
    public function build_client_parameters($redis_server) {
        if (empty($redis_server)) {
            // Attempt to automatically load Pantheon's Redis config from the env.
            if (isset($_SERVER['CACHE_HOST'])) {
                $redis_server = [
                    'host'     => $_SERVER['CACHE_HOST'],
                    'port'     => $_SERVER['CACHE_PORT'],
                    'auth'     => $_SERVER['CACHE_PASSWORD'],
                    'database' => isset($_SERVER['CACHE_DB']) ? $_SERVER['CACHE_DB'] : 0,
                ];
            } else {
                $redis_server = [
                    'host'     => '127.0.0.1',
                    'port'     => 6379,
                    'database' => 0,
                ];
            }
        }

        if (file_exists($redis_server['host']) && 'socket' === filetype($redis_server['host'])) { //unix socket connection
            //port must be null or socket won't connect
            $port = null;
        } else { //tcp connection
            $port = !empty($redis_server['port']) ? $redis_server['port'] : 6379;
        }

        $defaults = [
            'host'           => $redis_server['host'],
            'port'           => $port,
            'timeout'        => 1000, // I multiplied this by 1000 so we'd have a common measure of ms instead of s and ms, need to make sure this gets divided by 1000
            'retry_interval' => 100,
        ];
        // 1s timeout, 100ms delay between reconnections

        // merging the defaults with the original $redis_server enables any
        // custom parameters to get sent downstream to the redis client.
        return array_replace_recursive($redis_server, $defaults);
    }

    /**
     * Sets up the Redis connection (ie authentication and specific database).
     *
     * @param Redis $redis Redis client.
     * @param array $client_parameters Parameters used to configure Redis.
     * @param array $keys_methods Associative array of keys from
     *              $client_parameters to use as method arguments for $redis.
     * @return bool True if successful.
     */
    public function perform_client_connection($redis, $client_parameters, $keys_methods) {
        foreach ($keys_methods as $key => $method) {
            if (!isset($client_parameters[$key])) {
                continue;
            }
            try {
                $redis->$method($client_parameters[$key]);
            } catch (RedisException $e) {

                // PhpRedis throws an Exception when it fails a server call.
                // To prevent WordPress from fataling, we catch the Exception.
                throw new Exception($e->getMessage(), $e->getCode(), $e);
            }
        }
        return true;
    }

    /**
     * Returns a filterable array of expected Exception messages that may be thrown
     *
     * @return array Array of expected exception messages
     */
    public function retry_exception_messages() {
        $retry_exception_messages = ['socket error on read socket', 'Connection closed', 'Redis server went away'];
        return apply_filters('wp_redis_retry_exception_messages', $retry_exception_messages);
    }

    /**
     * Compares individual message to list of messages.
     *
     * @param string $error Message to compare
     * @param array $errors Array of messages to compare to
     * @return bool whether $error matches any items in $errors
     */
    public function exception_message_matches($error, $errors) {
        foreach ($errors as $message) {
            $pattern = $this->_format_message_for_pattern($message);
            $matches = (bool) preg_match($pattern, $error);
            if ($matches) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepends and appends '/' if not present in a string
     *
     * @param string $message Potential regex string that may need '/'
     * @return string Regex pattern
     */
    protected function _format_message_for_pattern($message) {
        $var = $message;
        $var = '/' === $var[0] ? $var : '/' . $var;
        $var = '/' === $var[strlen($var) - 1] ? $var : $var . '/';
        return $var;
    }

    /**
     * Handles exceptions by triggering a php error.
     *
     * @param Exception $exception
     * @return null
     */
    protected function _exception_handler($exception) {
        try {
            $this->last_triggered_error = 'WP Redis: ' . $exception->getMessage();
            // Be friendly to developers debugging production servers by triggering an error
            // @codingStandardsIgnoreStart
            trigger_error($this->last_triggered_error, E_USER_WARNING);
            // @codingStandardsIgnoreEnd
        } catch (PHPUnit_Framework_Error_Warning $e) {
            // PHPUnit throws an Exception when `trigger_error()` is called.
            // To ensure our tests (which expect Exceptions to be caught) continue to run,
            // we catch the PHPUnit exception and inspect the RedisException message
        }
    }

    /**
     * Admin UI to let the end user know something about the Redis connection isn't working.
     */
    public function wp_action_admin_notices_warn_missing_redis() {
        if (!current_user_can('manage_options') || empty($this->missing_redis_message)) {
            return;
        }
        echo '<div class="message error"><p>' . esc_html($this->missing_redis_message) . '</p></div>';
    }

    /**
     * Whether or not wakeup flush is enabled
     *
     * @return bool
     */
    private function is_redis_failback_flush_enabled() {
        if (defined('WP_INSTALLING') && WP_INSTALLING) {
            return false;
        } elseif (defined('WP_REDIS_DISABLE_FAILBACK_FLUSH') && WP_REDIS_DISABLE_FAILBACK_FLUSH) {
            return false;
        }
        return true;
    }

    /**
     * Will save the object cache before object is completely destroyed.
     *
     * Called upon object destruction, which should be when PHP ends.
     *
     * @return bool True value. Won't be used by PHP
     */
    public function __destruct() {
        return true;
    }

    private static $lua_scripts = [
        'decr-by-nover' => [],
        'incr-by-nover' => [],
        'set' => [],
    ];

    public static function initialize() {
        foreach (self::$lua_scripts as $name => &$value) {
            $script = file_get_contents(TAROSKY_WP_REDIS_PATCH_LUA_DIR . "/$name.lua");
            $sha1 = sha1($script);
            $value = [
                'script' => $script,
                'hash' => $sha1,
            ];
        }
    }

    public function is_connected() {
        return $this->_call_redis('isConnected');
    }

    private static function debug($message, ...$params) {
        if (defined('TAROSKY_WP_REDIS_PATCH_DEBUG') && TAROSKY_WP_REDIS_PATCH_DEBUG) {
            error_log("[WP_Object_Cache debug]$message: " . var_export($params, true));
        }
    }

    private static function error($message, ...$params) {
        error_log("[WP_Object_Cache error]$message: " . var_export($params, true));
        error_log(
            "[WP_Object_Cache error]stacktrace: " .
                var_export(debug_backtrace(), true),
        );
    }

    public function ensureLua($redis_client) {
        $files = array_values(self::$lua_scripts);
        $hashes = array_map(function ($s) {
            return $s['hash'];
        }, $files);
        $scripts = array_map(function ($s) {
            return $s['script'];
        }, $files);
        $existences = $redis_client->script('exists', ...$hashes);

        array_map(
            function ($script, $exists, $hash, $file) use ($redis_client) {
                if ($exists) {
                    return;
                }

                $res_hash = $redis_client->script('load', $script);
                if ($hash !== $res_hash) {
                    self::error("SHA1 hashes don't match", [
                        'name' => $file,
                        'calculated' => $hash,
                        'returned' => $res_hash,
                    ]);
                    $this->_call_redis('close');
                }
                self::debug('loaded a Lua script', $file);
                return;
            },
            $scripts,
            $existences,
            $hashes,
            $files,
        );
    }

    private static function clone($data) {
        return is_object($data) ? clone $data : $data;
    }

    protected function _should_use_redis_hashes($group) {
        return false;
    }

    /**
     * Utility function to generate the redis key for a given key and group.
     *
     * @param  string $key   The cache key.
     * @param  string $group The cache group.
     * @return string        A properly prefixed redis cache key.
     */
    protected function _key($key = '', $group = 'default') {
        if (empty($group)) {
            $group = 'default';
        }

        if (!empty($this->global_groups[$group])) {
            $prefix = $this->global_prefix;
        } else {
            $prefix = $this->blog_prefix;
        }

        if ($prefix === null) {
            $prefix = '';
        }

        return WP_CACHE_KEY_SALT .
            json_encode([strval($prefix), strval($group), strval($key)]);
    }

    // This function is primarily for testing.
    public function redis_key($key = '', $group = 'default') {
        return $this->_key($key, $group);
    }

    // This function is primarily for testing.
    public function version_key($key = '', $group = 'default') {
        return $this->_version_key($key, $group);
    }

    // This feature is not supported.
    public function delete_group($group) {
        return false;
    }

    /**
     * Constructs a PHPRedis Redis client.
     *
     * @param array $client_parameters Parameters used to construct a Redis client.
     * @return Redis Redis client.
     */
    public function prepare_client_connection($client_parameters) {
        $redis_client = new Redis();

        $redis_client->connect(
            $client_parameters['host'],
            $client_parameters['port'],
            $client_parameters['timeout'] / 1000,
            null,
            $client_parameters['retry_interval'],
        );

        $this->ensureLua($redis_client);

        return $redis_client;
    }

    private $versioned_redis_keys = [];
    private $versions = [];

    public static function encode_redis_string($data) {
        return is_numeric($data) && intval($data) === $data
            ? $data
            : igbinary_serialize($data);
    }

    public static function decode_redis_get($data) {
        if ($data === false) {
            return [false, false];
        }

        set_error_handler(function () use ($data) {
            throw new Exception("failed to unserialize: '$data'");
        }, E_WARNING);
        try {
            $result = is_numeric($data) ? intval($data) : igbinary_unserialize($data);
        } catch (Exception $e) {
            self::error('decode failure', $e->getMessage());
            return [false, false];
        } finally {
            restore_error_handler();
        }
        return [$result, true];
    }

    public static function decode_redis_del($result) {
        if (!is_numeric($result)) {
            self::error('unknown return value from del', $result);
            return 0;
        }
        return intval($result);
    }

    private function init_versioned_redis_keys() {
        global $redis_server_versioning_keys;
        foreach ($redis_server_versioning_keys as $gkey => $group) {
            foreach (array_keys($group) as $key) {
                $this->versioned_redis_keys[$this->_key($key, $gkey)] = true;
            }
        }
    }

    // Ignoring works as if the corresponding cache weren't there.
    private static function is_ignored($key, $group) {
        global $redis_server_ignored_keys;
        if (!$redis_server_ignored_keys) {
            $redis_server_ignored_keys = [];
        }
        $iks = $redis_server_ignored_keys;
        return array_key_exists($group, $iks) &&
            array_key_exists($key, $iks[$group]) &&
            $iks[$group][$key];
    }


    /**
     * Sets up object properties; PHP 5 style constructor
     *
     * @return null|WP_Object_Cache If cache is disabled, returns null.
     */
    public function __construct() {
        global $blog_id, $table_prefix, $wpdb;

        $this->multisite   = is_multisite();
        $this->blog_prefix = $this->multisite ? $blog_id . ':' : '';

        if (!$this->_connect_redis() && function_exists('add_action')) {
            add_action('admin_notices', [$this, 'wp_action_admin_notices_warn_missing_redis']);
        }

        if ($this->is_redis_failback_flush_enabled() && !empty($wpdb)) {
            if ($this->multisite) {
                $table = $wpdb->sitemeta;
                $col1  = 'meta_key';
                $col2  = 'meta_value';
            } else {
                $table = $wpdb->options;
                $col1  = 'option_name';
                $col2  = 'option_value';
            }
            // @codingStandardsIgnoreStart
            $this->do_redis_failback_flush = (bool) $wpdb->get_results("SELECT {$col2} FROM {$table} WHERE {$col1}='wp_redis_do_redis_failback_flush'");
            // @codingStandardsIgnoreEnd
            if ($this->is_redis_connected && $this->do_redis_failback_flush) {
                $ret = $this->_call_redis('flushdb');
                if ($ret) {
                    // @codingStandardsIgnoreStart
                    $wpdb->query("DELETE FROM {$table} WHERE {$col1}='wp_redis_do_redis_failback_flush'");
                    // @codingStandardsIgnoreEnd
                    $this->do_redis_failback_flush = false;
                }
            }
        }

        $this->global_prefix = ($this->multisite || defined('CUSTOM_USER_TABLE') && defined('CUSTOM_USER_META_TABLE')) ? '' : $table_prefix;

        /**
         * @todo This should be moved to the PHP4 style constructor, PHP5
         * already calls __destruct()
         */
        register_shutdown_function([$this, '__destruct']);

        $this->init_versioned_redis_keys();
    }

    public function should_version($redis_key) {
        return isset($this->versioned_redis_keys[$redis_key]);
    }

    public function set_cache_version($redis_key, $version) {
        $this->versions[$redis_key] = $version;
    }

    public function clear_cache_version($redis_key) {
        unset($this->versions[$redis_key]);
    }

    public function flush_cache_versions() {
        $this->versions = [];
    }

    public function get_cache_version($redis_key) {
        return isset($this->versions[$redis_key]) ? $this->versions[$redis_key] : null;
    }

    protected function _version_key($key = '', $group = 'default') {
        if (empty($group)) {
            $group = 'default';
        }

        if (!empty($this->global_groups[$group])) {
            $prefix = $this->global_prefix;
        } else {
            $prefix = $this->blog_prefix;
        }

        return WP_CACHE_VERSION_KEY_SALT .
            json_encode([strval($prefix), strval($group), strval($key)]);
    }

    // Versions are expressed as UUID v4.
    public function generate_version() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff),
        );
    }

    private function call_set($key, $group, $params) {
        $redis_key = $params['key'];

        if (!$this->should_version($redis_key)) {
            $ps = [$redis_key, $params['value']];
            $opt = [];
            if ($params['nx']) {
                $opt[] = 'nx';
            }
            if ($params['xx']) {
                $opt[] = 'xx';
            }
            if ($params['ex'] !== null) {
                $opt['ex'] = $params['ex'];
            }
            if ($params['px'] !== null) {
                $opt['px'] = $params['px'];
            }
            if ($opt) {
                $ps[] = $opt;
            }
            return $this->_call_redis('set', ...$ps);
        }

        $new_version = $this->generate_version();

        $result = $this->_call_redis(
            'evalSha',
            self::$lua_scripts['set']['hash'],
            [
                $redis_key,
                $this->_version_key($key, $group),
                $this->get_cache_version($redis_key) ?? '',
                $new_version,
                $params['value'],
                $params['ex'] ?? '',
                $params['px'] ?? '',
                var_export($params['nx'], true),
                var_export($params['xx'], true),
                var_export($params['keepttl'], true),
            ],
            2,
        );

        if ($result === false) {
            $this->clear_cache_version($redis_key);
            $this->_unset_internal($key, $group);
            $this->debug('found inconsistent update during SET operation', $redis_key);
            return false;
        }

        $this->set_cache_version($redis_key, $new_version);
        return $result;
    }

    /**
     * Sets the data contents into the cache
     *
     * The cache contents is grouped by the $group parameter followed by the
     * $key. This allows for duplicate ids in unique groups. Therefore, naming of
     * the group should be used with care and should follow normal function
     * naming guidelines outside of core WordPress usage.
     *
     * The $expire parameter is not used, because the cache will automatically
     * expire for each time a page is accessed and PHP finishes. The method is
     * more for cache plugins which use files.
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param string $group Where to group the cache contents
     * @param int $expire TTL for the data, in seconds
     * @return bool Always returns true
     */
    public function set(
        $key,
        $data,
        $group = 'default',
        $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS
    ) {
        if (function_exists('wp_suspend_cache_addition') && wp_suspend_cache_addition()) {
            return false;
        }

        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return true;
        }

        if (!$this->_should_persist($group)) {
            $this->_set_internal($key, $group, self::clone($data));
            return true;
        }

        $set_params = $this->new_set_param(
            $this->_key($key, $group),
            self::encode_redis_string($data),
        );
        if ($expire) {
            $set_params['ex'] = $expire;
        }
        $succeeded = $this->call_set($key, $group, $set_params);

        if ($succeeded) {
            $this->_set_internal($key, $group, self::clone($data));
        } else {
            $this->_unset_internal($key, $group);
        }

        return $succeeded;
    }

    /**
     * Replace the contents in the cache, if contents already exist
     * @see WP_Object_Cache::set()
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param string $group Where to group the cache contents
     * @param int $expire When to expire the cache contents
     * @return bool False if not exists, true if contents were replaced
     */
    public function replace(
        $key,
        $data,
        $group = 'default',
        $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS
    ) {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return false;
        }

        if (!$this->_should_persist($group)) {
            return false;
        }

        $set_params = $this->new_set_param(
            $this->_key($key, $group),
            self::encode_redis_string($data),
        );
        $set_params['xx'] = true;
        if ($expire) {
            $set_params['ex'] = $expire;
        }
        $succeeded = $this->call_set($key, $group, $set_params);

        if ($succeeded) {
            $this->_set_internal($key, $group, self::clone($data));
        } else {
            $this->_unset_internal($key, $group);
        }

        return $succeeded;
    }

    /**
     * Retrieves the cache contents, if it exists
     *
     * The contents will be first attempted to be retrieved by searching by the
     * key in the cache group. If the cache is hit (success) then the contents
     * are returned.
     *
     * On failure, the number of cache misses will be incremented.
     *
     * @param int|string $key What the contents in the cache are called
     * @param string $group Where the cache contents are grouped
     * @param string $force Whether to force a refetch rather than relying on the local cache (default is false)
     * @param bool $found Optional. Whether the key was found in the cache. Disambiguates a return of false, a storable value. Passed by reference. Default null.
     * @return bool|mixed False on failure to retrieve contents or the cache contents on success
     */
    public function get($key, $group = 'default', $force = false, &$found = null) {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            $found = false;
            return false;
        }

        // Key is set internally, so we can use this value
        if ($this->_isset_internal($key, $group) && !$force) {
            $found = true;
            return $this->_get_internal($key, $group);
        }

        // Not a persistent group, so don't try Redis if the value doesn't exist
        // internally
        if (!$this->_should_persist($group)) {
            $found = false;
            return false;
        }

        try {
            $redis_key = $this->_key($key, $group);

            if (!$this->should_version($redis_key)) {
                list($value, $found) = self::decode_redis_get(
                    $this->_call_redis('get', $redis_key),
                );
                return $value;
            }

            $res = $this->_call_redis('mget', [
                $redis_key,
                $this->_version_key($key, $group),
            ]);
            if ($res === false) {
                return false;
            }

            [$res2, $actual_version] = $res;
            if ($actual_version === false) {
                $this->clear_cache_version($redis_key);
            } else {
                $this->set_cache_version($redis_key, $actual_version);
            }

            list($value, $found) = self::decode_redis_get($res2);
            return $value;
        } finally {
            if ($found) {
                $this->_set_internal($key, $group, $value);
            } else {
                $this->_unset_internal($key, $group);
            }
        }
    }

    private function call_mget($redis_keys, $redis_group_key_indexes) {
        if (!$redis_keys) {
            return [];
        }

        $should_version_flags = [];
        $redis_params = [];

        array_map(
            function ($redis_key, $group_key) use (
                &$redis_params,
                &$should_version_flags
            ) {
                $should_version = $this->should_version($redis_key);
                $should_version_flags[] = $should_version;
                $redis_params[] = $redis_key;
                if ($should_version) {
                    $redis_params[] = $this->_version_key($group_key[1], $group_key[0]);
                }
                return;
            },
            $redis_keys,
            $redis_group_key_indexes,
        );

        $res_count = 0;
        $res_array = $this->_call_redis('mget', $redis_params);
        if ($res_array === false) {
            return array_fill(0, count($redis_keys), false);
        }
        $results = [];
        foreach ($redis_keys as $i => $redis_key) {
            $results[] = $res_array[$res_count++];
            if ($should_version_flags[$i]) {
                $version = $res_array[$res_count++];
                if ($version === false) {
                    $this->clear_cache_version($redis_key);
                } else {
                    $this->set_cache_version($redis_key, $version);
                }
            }
        }
        assert($res_count == count($res_array));

        return $results;
    }

    /**
     * Retrieves multiple values from the cache in one call.
     *
     * @param array  $keys  Array of keys under which the cache contents are stored.
     * @param string $group Optional. Where the cache contents are grouped. Default empty.
     * @param bool   $force Optional. Whether to force an update of the local cache
     *                      from the persistent cache. Default false.
     * @return array Array of values organized into groups.
     */
    public function get_multiple($keys, $group = 'default', $force = false) {
        $normal_res = $this->normalized_get_multiple([$group => $keys], $force);
        $group_kv = array_key_exists($group, $normal_res) ? $normal_res[$group] : [];

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = array_key_exists($key, $group_kv) ? $group_kv[$key] : false;
        }
        return $result;
    }

    public function normalized_get_multiple($groups, $force = false) {
        $get_from_cache = function ($key, $group) {
            $found = $this->_isset_internal($key, $group);

            return [$found ? $this->_get_internal($key, $group) : null, $found];
        };

        if (empty($groups) || !is_array($groups)) {
            return false;
        }

        $cache = [];
        $redis_group_key_indexes = [];
        $redis_keys = [];

        foreach ($groups as $group => $keys) {
            if (empty($group)) {
                $group = 'default';
            }

            if (!$this->_should_persist($group)) {
                foreach ($keys as $key) {
                    if ($this->is_ignored($key, $group)) {
                        continue;
                    }
                    list($value, $found) = $get_from_cache($key, $group);
                    if ($found) {
                        $cache[$group][$key] = $value;
                    }
                }
                continue;
            }

            foreach ($keys as $key) {
                if ($this->is_ignored($key, $group)) {
                    continue;
                }

                if (!$force && $this->_isset_internal($key, $group)) {
                    $cache[$group][$key] = $this->_get_internal($key, $group);
                    continue;
                }

                $redis_keys[] = $this->_key($key, $group);
                $redis_group_key_indexes[] = [$group, $key];
            }
        }

        if ($redis_keys) {
            $redis_result = $this->call_mget($redis_keys, $redis_group_key_indexes);

            array_map(
                function ($result, $group_key) use (&$cache) {
                    list($value, $found) = $result;
                    list($group, $key) = $group_key;

                    if ($found) {
                        $cache[$group][$key] = $value;
                        $this->_set_internal($key, $group, $value);
                    }

                    return null;
                },
                array_map([$this, 'decode_redis_get'], $redis_result),
                $redis_group_key_indexes,
            );
        }

        return $cache;
    }

    /**
     * Remove the contents of the cache key in the group
     *
     * If the cache key does not exist in the group and $force parameter is set
     * to false, then nothing will happen. The $force parameter is set to false
     * by default.
     *
     * @param int|string $key What the contents in the cache are called
     * @param string $group Where the cache contents are grouped
     * @param bool $force Optional. Whether to force the unsetting of the cache
     *     key in the group
     * @return bool False if the contents weren't deleted and true on success
     */
    public function delete($key, $group = 'default', $force = true) {
        // `force` param is ignored.
        if (empty($group)) {
            $group = 'default';
        }

        try {
            if (!$this->_should_persist($group)) {
                return true;
            }

            $redis_key = $this->_key($key, $group);

            if (!$this->should_version($redis_key)) {
                return self::decode_redis_del($this->_call_redis('del', $redis_key)) == 1;
            }

            $this->clear_cache_version($redis_key);
            $res = $this->_call_redis('del', [$redis_key, $this->_version_key($key, $group)]);
            return 0 < self::decode_redis_del($res);
        } finally {
            $this->_unset_internal($key, $group);
        }
    }

    private static function new_set_param($key, $value) {
        return [
            'key' => $key,
            'value' => $value,
            'ex' => null, // EX must be greater than 0.
            'px' => null, // PX must be greater than 0.
            'nx' => false,
            'xx' => false,
            'keepttl' => false,
        ];
    }

    /**
     * Adds data to the cache if it doesn't already exist.
     *
     * @uses WP_Object_Cache::_exists Checks to see if the cache already has data.
     * @uses WP_Object_Cache::set Sets the data after the checking the cache
     *     contents existence.
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param string $group Where to group the cache contents
     * @param int $expire When to expire the cache contents
     * @return bool False if cache key and group already exist, true on success
     */
    public function add(
        $key,
        $data,
        $group = 'default',
        $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS
    ) {
        if (function_exists('wp_suspend_cache_addition') && wp_suspend_cache_addition()) {
            return false;
        }

        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return true;
        }

        if (!$this->_should_persist($group)) {
            return false;
        }

        $set_params = $this->new_set_param(
            $this->_key($key, $group),
            self::encode_redis_string($data),
        );
        $set_params['nx'] = true;
        if ($expire) {
            $set_params['ex'] = $expire;
        }
        $succeeded = $this->call_set($key, $group, $set_params);

        if ($succeeded) {
            $this->_set_internal($key, $group, self::clone($data));
        } else {
            $this->_unset_internal($key, $group);
        }

        return $succeeded;
    }

    /**
     * Decrement numeric cache item's value
     *
     * @param int|string $key The cache key to increment
     * @param int $offset The amount by which to decrement the item's value. Default is 1.
     * @param string $group The group the key is in.
     * @return false|int False on failure, the item's new value on success.
     */
    public function decr($key, $offset = 1, $group = 'default') {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return false;
        }

        $offset = (int) $offset;

        // If this isn't a persistant group, we have to sort this out ourselves, grumble grumble.
        if (!$this->_should_persist($group)) {
            if (!$this->_isset_internal($key, $group)) {
                return false;
            }

            $existing = $this->_get_internal($key, $group);
            if (!is_numeric($existing)) {
                $existing = 0;
            } else {
                $existing -= $offset;
            }
            if ($existing < 0) {
                $existing = 0;
            }
            $this->_set_internal($key, $group, $existing);
            return $existing;
        }

        $result = $this->_call_redis(
            'evalSha',
            self::$lua_scripts['decr-by-nover']['hash'],
            [$this->_key($key, $group), $offset],
            1,
        );

        if (is_int($result)) {
            $this->_set_internal($key, $group, $result);
        }
        return $result;
    }

    /**
     * Increment numeric cache item's value
     *
     * @param int|string $key The cache key to increment
     * @param int $offset The amount by which to increment the item's value. Default is 1.
     * @param string $group The group the key is in.
     * @return false|int False on failure, the item's new value on success.
     */
    public function incr($key, $offset = 1, $group = 'default') {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return false;
        }

        $offset = (int) $offset;

        // If this isn't a persistant group, we have to sort this out ourselves, grumble grumble.
        if (!$this->_should_persist($group)) {
            if (!$this->_isset_internal($key, $group)) {
                return false;
            }

            $existing = $this->_get_internal($key, $group);
            if (!is_numeric($existing)) {
                $existing = 1;
            } else {
                $existing += $offset;
            }
            if ($existing < 0) {
                $existing = 0;
            }
            $this->_set_internal($key, $group, $existing);
            return $existing;
        }

        $result = $this->_call_redis(
            'evalSha',
            self::$lua_scripts['incr-by-nover']['hash'],
            [$this->_key($key, $group), $offset],
            1,
        );

        if (is_int($result)) {
            $this->_set_internal($key, $group, $result);
        }
        return $result;
    }

    /**
     * Clears the object cache of all data.
     *
     * By default, this will flush the session cache as well as Redis, but we
     * can leave the redis cache intact if we want. This is helpful when, for
     * instance, you're running a batch process and want to clear the session
     * store to reduce the memory footprint, but you don't want to have to
     * re-fetch all the values from the database.
     *
     * @param  bool $redis Should we flush redis as well as the session cache?
     * @return bool Always returns true
     */
    public function flush($redis = true) {
        $this->flush_cache_versions();
        $this->cache = [];
        if ($redis) {
            $this->_call_redis('flushdb');
        }

        return true;
    }


    /**
     * Wrapper method for calls to Redis, which fails gracefully when Redis is unavailable
     *
     * @param string $method
     * @param mixed $args
     * @return mixed
     */
    public function _call_redis($method) {
        global $wpdb;

        $arguments = func_get_args();
        array_shift($arguments); // ignore $method

        // $group is intended for the failback, and isn't passed to the Redis callback
        if ('hIncrBy' === $method) {
            $group = array_pop($arguments);
        }

        if ($this->is_redis_connected) {
            try {
                if (!isset($this->redis_calls[$method])) {
                    $this->redis_calls[$method] = 0;
                }
                $this->redis_calls[$method]++;
                $retval = call_user_func_array([$this->redis, $method], $arguments);
                return $retval;
            } catch (Exception $e) {
                $retry_exception_messages = $this->retry_exception_messages();
                // PhpRedis throws an Exception when it fails a server call.
                // To prevent WordPress from fataling, we catch the Exception.
                if ($this->exception_message_matches($e->getMessage(), $retry_exception_messages)) {

                    $this->_exception_handler($e);

                    // Attempt to refresh the connection if it was successfully established once
                    // $this->is_redis_connected will be set inside _connect_redis()
                    if ($this->_connect_redis()) {
                        return call_user_func_array([$this, '_call_redis'], array_merge([$method], $arguments));
                    }
                    // Fall through to fallback below
                } else {
                    throw $e;
                }
            }
        } // End if().

        if ($this->is_redis_failback_flush_enabled() && !$this->do_redis_failback_flush && !empty($wpdb)) {
            if ($this->multisite) {
                $table = $wpdb->sitemeta;
                $col1  = 'meta_key';
                $col2  = 'meta_value';
            } else {
                $table = $wpdb->options;
                $col1  = 'option_name';
                $col2  = 'option_value';
            }
            // @codingStandardsIgnoreStart
            $wpdb->query("INSERT IGNORE INTO {$table} ({$col1},{$col2}) VALUES ('wp_redis_do_redis_failback_flush',1)");
            // @codingStandardsIgnoreEnd
            $this->do_redis_failback_flush = true;
        }

        // Mock expected behavior from Redis for these methods
        switch ($method) {
            case 'incr':
            case 'incrBy':
                $val    = $this->cache[$arguments[0]];
                $offset = isset($arguments[1]) && 'incrBy' === $method ? $arguments[1] : 1;
                $val    = $val + $offset;
                return $val;
            case 'hIncrBy':
                $val = $this->_get_internal($arguments[1], $group);
                return $val + $arguments[2];
            case 'decrBy':
            case 'decr':
                $val    = $this->cache[$arguments[0]];
                $offset = isset($arguments[1]) && 'decrBy' === $method ? $arguments[1] : 1;
                $val    = $val - $offset;
                return $val;
            case 'del':
            case 'hDel':
                return 1;
            case 'flushAll':
            case 'flushdb':
            case 'IsConnected':
            case 'exists':
            case 'get':
            case 'mget':
            case 'hGet':
            case 'hmGet':
                return false;
        }
    }
}

WP_Object_Cache::initialize();
