<?php

/**
 * Originally created by Pantheon as https://github.com/pantheon-systems/wp-redis
 * Modified by Tarosky INC.
 */

if (!defined('WP_CACHE_KEY_SALT')) {
    define('WP_CACHE_KEY_SALT', '');
}

/**
 * Adds data to the cache, if the cache key doesn't already exist.
 *
 * @since 2.0.0
 *
 * @see WP_Object_Cache::add()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    The cache key to use for retrieval later.
 * @param mixed      $data   The data to add to the cache.
 * @param string     $group  Optional. The group to add the cache to. Enables the same key
 *                           to be used across groups. Default empty.
 * @param int        $expire Optional. When the cache data should expire, in seconds.
 *                           Default 0 (no expiration).
 * @return bool True on success, false if cache key and group already exist.
 */
function wp_cache_add($key, $data, $group = '', $expire = 0) {
    global $wp_object_cache;

    return $wp_object_cache->add($key, $data, $group, (int) $expire);
}

/**
 * Closes the cache.
 *
 * This function has ceased to do anything since WordPress 2.5. The
 * functionality was removed along with the rest of the persistent cache.
 *
 * This does not mean that plugins can't implement this function when they need
 * to make sure that the cache is cleaned up after WordPress no longer needs it.
 *
 * @since 2.0.0
 *
 * @return true Always returns true.
 */
function wp_cache_close() {
    return true;
}

/**
 * Decrements numeric cache item's value.
 *
 * @since 3.3.0
 *
 * @see WP_Object_Cache::decr()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    The cache key to decrement.
 * @param int        $offset Optional. The amount by which to decrement the item's value. Default 1.
 * @param string     $group  Optional. The group the key is in. Default empty.
 * @return int|false The item's new value on success, false on failure.
 */
function wp_cache_decr($key, $offset = 1, $group = '') {
    global $wp_object_cache;

    return $wp_object_cache->decr($key, $offset, $group);
}

/**
 * Removes the cache contents matching key and group.
 *
 * @since 2.0.0
 *
 * @see WP_Object_Cache::delete()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key   What the contents in the cache are called.
 * @param string     $group Optional. Where the cache contents are grouped. Default empty.
 * @return bool True on successful removal, false on failure.
 */
function wp_cache_delete($key, $group = '') {
    global $wp_object_cache;

    return $wp_object_cache->delete($key, $group);
}

/**
 * Removes all cache items.
 *
 * @since 2.0.0
 *
 * @see WP_Object_Cache::flush()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @return bool True on success, false on failure.
 */
function wp_cache_flush() {
    global $wp_object_cache;

    return $wp_object_cache->flush();
}

/**
 * Retrieves the cache contents from the cache by key and group.
 *
 * @since 2.0.0
 *
 * @see WP_Object_Cache::get()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key   The key under which the cache contents are stored.
 * @param string     $group Optional. Where the cache contents are grouped. Default empty.
 * @param bool       $force Optional. Whether to force an update of the local cache
 *                          from the persistent cache. Default false.
 * @param bool       $found Optional. Whether the key was found in the cache (passed by reference).
 *                          Disambiguates a return of false, a storable value. Default null.
 * @return mixed|false The cache contents on success, false on failure to retrieve contents.
 */
function wp_cache_get($key, $group = '', $force = false, &$found = null) {
    global $wp_object_cache;

    return $wp_object_cache->get($key, $group, $force, $found);
}

/**
 * Retrieves multiple values from the cache in one call.
 *
 * @since 5.5.0
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
 * @since 3.3.0
 *
 * @see WP_Object_Cache::incr()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    The key for the cache contents that should be incremented.
 * @param int        $offset Optional. The amount by which to increment the item's value. Default 1.
 * @param string     $group  Optional. The group the key is in. Default empty.
 * @return int|false The item's new value on success, false on failure.
 */
function wp_cache_incr($key, $offset = 1, $group = '') {
    global $wp_object_cache;

    return $wp_object_cache->incr($key, $offset, $group);
}

/**
 * Sets up Object Cache Global and assigns it.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 */
function wp_cache_init() {
    $GLOBALS['wp_object_cache'] = new WP_Object_Cache();
}

/**
 * Replaces the contents of the cache with new data.
 *
 * @since 2.0.0
 *
 * @see WP_Object_Cache::replace()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    The key for the cache data that should be replaced.
 * @param mixed      $data   The new data to store in the cache.
 * @param string     $group  Optional. The group for the cache data that should be replaced.
 *                           Default empty.
 * @param int        $expire Optional. When to expire the cache contents, in seconds.
 *                           Default 0 (no expiration).
 * @return bool False if original value does not exist, true if contents were replaced
 */
function wp_cache_replace($key, $data, $group = '', $expire = 0) {
    global $wp_object_cache;

    return $wp_object_cache->replace($key, $data, $group, (int) $expire);
}

/**
 * Saves the data to the cache.
 *
 * Differs from wp_cache_add() and wp_cache_replace() in that it will always write data.
 *
 * @since 2.0.0
 *
 * @see WP_Object_Cache::set()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    The cache key to use for retrieval later.
 * @param mixed      $data   The contents to store in the cache.
 * @param string     $group  Optional. Where to group the cache contents. Enables the same key
 *                           to be used across groups. Default empty.
 * @param int        $expire Optional. When to expire the cache contents, in seconds.
 *                           Default 0 (no expiration).
 * @return bool True on success, false on failure.
 */
function wp_cache_set($key, $data, $group = '', $expire = 0) {
    global $wp_object_cache;

    return $wp_object_cache->set($key, $data, $group, (int) $expire);
}

/**
 * Switches the internal blog ID.
 *
 * This changes the blog id used to create keys in blog specific groups.
 *
 * @since 3.5.0
 *
 * @see WP_Object_Cache::switch_to_blog()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int $blog_id Site ID.
 */
function wp_cache_switch_to_blog($blog_id) {
    global $wp_object_cache;

    $wp_object_cache->switch_to_blog($blog_id);
}

/**
 * Adds a group or set of groups to the list of global groups.
 *
 * @since 2.6.0
 *
 * @see WP_Object_Cache::add_global_groups()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param string|string[] $groups A group or an array of groups to add.
 */
function wp_cache_add_global_groups($groups) {
    global $wp_object_cache;

    $wp_object_cache->add_global_groups($groups);
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @since 2.6.0
 *
 * @see WP_Object_Cache::add_non_persistent_groups()
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param string|string[] $groups A group or an array of groups to add.
 */
function wp_cache_add_non_persistent_groups($groups) {
    global $wp_object_cache;

    $wp_object_cache->add_non_persistent_groups($groups);
}

/**
 * Reset internal cache keys and structures.
 *
 * If the cache back end uses global blog or site IDs as part of its cache keys,
 * this function instructs the back end to reset those keys and perform any cleanup
 * since blog or site IDs have changed since cache init.
 *
 * This function is deprecated. Use wp_cache_switch_to_blog() instead of this
 * function when preparing the cache for a blog switch. For clearing the cache
 * during unit tests, consider using wp_cache_init(). wp_cache_init() is not
 * recommended outside of unit tests as the performance penalty for using it is
 * high.
 *
 * @since 2.6.0
 * @deprecated 3.5.0 wp_cache_switch_to_blog()
 */
function wp_cache_reset() {
    _deprecated_function(__FUNCTION__, '3.5.0', 'wp_cache_switch_to_blog()');
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
     */
    public $cache = [];

    /**
     * The amount of times a request was made to Redis
     */
    public $redis_calls = [];

    /**
     * List of global groups
     */
    public $global_groups = [];

    /**
     * List of non-persistent groups
     */
    public $non_persistent_groups = [];

    /**
     * The blog prefix to prepend to keys in non-global groups.
     */
    public $blog_prefix;

    /**
     * Whether or not Redis is connected
     */
    public $is_redis_connected = false;

    /**
     * Whether or not the object cache thinks Redis needs a flush
     */
    public $do_redis_failback_flush = false;

    /**
     * The last triggered error
     */
    public $last_triggered_error = '';

    /**
     * Sets the list of global groups.
     *
     * @param array $groups List of groups that are global.
     */
    public function add_global_groups($groups) {
        $groups = (array) $groups;

        $groups = array_fill_keys($groups, true);
        $this->global_groups = array_merge($this->global_groups, $groups);
    }

    /**
     * Sets the list of non-persistent groups.
     *
     * @param array $groups List of groups that are non-persistent.
     */
    public function add_non_persistent_groups($groups) {
        $groups = (array) $groups;

        $groups = array_fill_keys($groups, true);
        $this->non_persistent_groups = array_merge($this->non_persistent_groups, $groups);
    }

    /**
     * Switch the interal blog id.
     *
     * This changes the blog id used to create keys in blog specific groups.
     *
     * @param int $blog_id Blog ID
     */
    public function switch_to_blog($blog_id) {
        $blog_id = (int) $blog_id;
        $this->blog_prefix = $this->multisite ? $blog_id . ':' : '';
    }

    /**
     * Check whether there's a value in the internal object cache.
     *
     * @param string $key
     * @param string $group
     * @return boolean
     */
    private function isset_internal($key, $group) {
        $key = $this->key($key, $group);
        return array_key_exists($key, $this->cache);
    }

    /**
     * Get a value from the internal object cache
     *
     * @param string $key
     * @param string $group
     * @return mixed
     */
    private function get_internal($key, $group) {
        $value = null;
        $key = $this->key($key, $group);
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
    private function set_internal($key, $group, $value) {
        $key = $this->key($key, $group);
        $this->cache[$key] = $value;
    }

    /**
     * Unset a value from the internal object cache
     *
     * @param string $key
     * @param string $group
     */
    private function unset_internal($key, $group) {
        $key = $this->key($key, $group);
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
    private function should_persist($group) {
        return empty($this->non_persistent_groups[$group]);
    }

    /**
     * Wrapper method for connecting to Redis, which lets us retry the connection
     */
    private function connect_redis() {
        global $redis_server;

        try {
            $this->redis = $this->prepare_client_connection($redis_server);
        } catch (Exception $e) {
            $this->exception_handler($e);
            $this->is_redis_connected = false;
            return false;
        }

        $this->is_redis_connected = $this->redis->isConnected();
        return $this->is_redis_connected;
    }

    /**
     * Check the error message and return true if the call is retriable.
     *
     * @param string $error Message to compare
     * @return bool whether $error is retriable
     */
    public function is_retriable_error_message($error) {
        $retriable_error_messages = [
            'socket error on read socket',
            'Connection closed',
            'Redis server went away',
        ];

        foreach ($retriable_error_messages as $msg) {
            if (strpos($error, $msg) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Handles exceptions by triggering a php error.
     *
     * @param Exception $exception
     * @return null
     */
    private function exception_handler($exception) {
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
        return $this->call_redis('isConnected');
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
                    $this->call_redis('close');
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

    /**
     * Utility function to generate the redis key for a given key and group.
     *
     * @param  string $key   The cache key.
     * @param  string $group The cache group.
     * @return string        A properly prefixed redis cache key.
     */
    public function key($key = '', $group = 'default') {
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

    /**
     * Constructs a PHPRedis client.
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
            return 0;
        }
        return intval($result);
    }

    private function init_versioned_redis_keys() {
        global $redis_server_versioning_keys;
        foreach ($redis_server_versioning_keys as $gkey => $group) {
            foreach (array_keys($group) as $key) {
                $this->versioned_redis_keys[$this->key($key, $gkey)] = true;
            }
        }
    }

    private static function is_ignored($key, $group) {
        // Ignoring works as if the corresponding cache weren't there.
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

        $this->connect_redis();

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
                $ret = $this->call_redis('flushdb');
                if ($ret) {
                    // @codingStandardsIgnoreStart
                    $wpdb->query("DELETE FROM {$table} WHERE {$col1}='wp_redis_do_redis_failback_flush'");
                    // @codingStandardsIgnoreEnd
                    $this->do_redis_failback_flush = false;
                }
            }
        }

        $this->global_prefix = ($this->multisite || defined('CUSTOM_USER_TABLE') && defined('CUSTOM_USER_META_TABLE')) ? '' : $table_prefix;

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

    public function version_key($key = '', $group = 'default') {
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

    public function generate_version() {
        // Versions are expressed as UUID v4.
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
            return $this->call_redis('set', ...$ps);
        }

        $new_version = $this->generate_version();

        $result = $this->call_redis(
            'evalSha',
            self::$lua_scripts['set']['hash'],
            [
                $redis_key,
                $this->version_key($key, $group),
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
            $this->unset_internal($key, $group);
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
    public function set($key, $data, $group = 'default', $expire = 0) {
        if (function_exists('wp_suspend_cache_addition') && wp_suspend_cache_addition()) {
            return false;
        }

        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return true;
        }

        if (!$this->should_persist($group)) {
            $this->set_internal($key, $group, self::clone($data));
            return true;
        }

        $set_params = $this->new_set_param(
            $this->key($key, $group),
            self::encode_redis_string($data),
        );
        if ($expire) {
            $set_params['ex'] = $expire;
        }
        $succeeded = $this->call_set($key, $group, $set_params);

        if ($succeeded) {
            $this->set_internal($key, $group, self::clone($data));
        } else {
            $this->unset_internal($key, $group);
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
    public function replace($key, $data, $group = 'default', $expire = 0) {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return false;
        }

        if (!$this->should_persist($group)) {
            return false;
        }

        $set_params = $this->new_set_param(
            $this->key($key, $group),
            self::encode_redis_string($data),
        );
        $set_params['xx'] = true;
        if ($expire) {
            $set_params['ex'] = $expire;
        }
        $succeeded = $this->call_set($key, $group, $set_params);

        if ($succeeded) {
            $this->set_internal($key, $group, self::clone($data));
        } else {
            $this->unset_internal($key, $group);
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
        if ($this->isset_internal($key, $group) && !$force) {
            $found = true;
            return $this->get_internal($key, $group);
        }

        // Not a persistent group, so don't try Redis if the value doesn't exist
        // internally
        if (!$this->should_persist($group)) {
            $found = false;
            return false;
        }

        try {
            $redis_key = $this->key($key, $group);

            if (!$this->should_version($redis_key)) {
                list($value, $found) = self::decode_redis_get(
                    $this->call_redis('get', $redis_key),
                );
                return $value;
            }

            $res = $this->call_redis('mget', [
                $redis_key,
                $this->version_key($key, $group),
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
                $this->set_internal($key, $group, $value);
            } else {
                $this->unset_internal($key, $group);
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
                    $redis_params[] = $this->version_key($group_key[1], $group_key[0]);
                }
                return;
            },
            $redis_keys,
            $redis_group_key_indexes,
        );

        $res_count = 0;
        $res_array = $this->call_redis('mget', $redis_params);
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
            $found = $this->isset_internal($key, $group);

            return [$found ? $this->get_internal($key, $group) : null, $found];
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

            if (!$this->should_persist($group)) {
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

                if (!$force && $this->isset_internal($key, $group)) {
                    $cache[$group][$key] = $this->get_internal($key, $group);
                    continue;
                }

                $redis_keys[] = $this->key($key, $group);
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
                        $this->set_internal($key, $group, $value);
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
            if (!$this->should_persist($group)) {
                return true;
            }

            $redis_key = $this->key($key, $group);

            if (!$this->should_version($redis_key)) {
                return self::decode_redis_del($this->call_redis('del', $redis_key)) == 1;
            }

            $this->clear_cache_version($redis_key);
            $res = $this->call_redis('del', [$redis_key, $this->version_key($key, $group)]);
            return 0 < self::decode_redis_del($res);
        } finally {
            $this->unset_internal($key, $group);
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
    public function add($key, $data, $group = 'default', $expire = 0) {
        if (function_exists('wp_suspend_cache_addition') && wp_suspend_cache_addition()) {
            return false;
        }

        if (empty($group)) {
            $group = 'default';
        }

        if ($this->is_ignored($key, $group)) {
            return true;
        }

        if (!$this->should_persist($group)) {
            return false;
        }

        $set_params = $this->new_set_param(
            $this->key($key, $group),
            self::encode_redis_string($data),
        );
        $set_params['nx'] = true;
        if ($expire) {
            $set_params['ex'] = $expire;
        }
        $succeeded = $this->call_set($key, $group, $set_params);

        if ($succeeded) {
            $this->set_internal($key, $group, self::clone($data));
        } else {
            $this->unset_internal($key, $group);
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
        if (!$this->should_persist($group)) {
            if (!$this->isset_internal($key, $group)) {
                return false;
            }

            $existing = $this->get_internal($key, $group);
            if (!is_numeric($existing)) {
                $existing = 0;
            } else {
                $existing -= $offset;
            }
            if ($existing < 0) {
                $existing = 0;
            }
            $this->set_internal($key, $group, $existing);
            return $existing;
        }

        $result = $this->call_redis(
            'evalSha',
            self::$lua_scripts['decr-by-nover']['hash'],
            [$this->key($key, $group), $offset],
            1,
        );

        if (is_int($result)) {
            $this->set_internal($key, $group, $result);
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
        if (!$this->should_persist($group)) {
            if (!$this->isset_internal($key, $group)) {
                return false;
            }

            $existing = $this->get_internal($key, $group);
            if (!is_numeric($existing)) {
                $existing = 1;
            } else {
                $existing += $offset;
            }
            if ($existing < 0) {
                $existing = 0;
            }
            $this->set_internal($key, $group, $existing);
            return $existing;
        }

        $result = $this->call_redis(
            'evalSha',
            self::$lua_scripts['incr-by-nover']['hash'],
            [$this->key($key, $group), $offset],
            1,
        );

        if (is_int($result)) {
            $this->set_internal($key, $group, $result);
        }
        return $result;
    }

    /**
     * Clears the object cache of all data.
     *
     * @return bool True on success, false on failure.
     */
    public function flush() {
        $this->flush_cache_versions();
        $this->cache = [];
        return $this->call_redis('flushdb');
    }

    /**
     * Wrapper method for calls to Redis, which fails gracefully when Redis is unavailable
     *
     * @param string $method
     * @param mixed $args
     * @return mixed
     */
    public function call_redis($method) {
        global $wpdb;

        $arguments = func_get_args();
        array_shift($arguments); // ignore $method

        if ($this->is_redis_connected) {
            try {
                if (!isset($this->redis_calls[$method])) {
                    $this->redis_calls[$method] = 0;
                }
                $this->redis_calls[$method]++;
                return call_user_func_array([$this->redis, $method], $arguments);
            } catch (Exception $e) {
                if ($this->is_retriable_error_message($e->getMessage())) {
                    $this->exception_handler($e);

                    // Attempt to refresh the connection if it was successfully established once
                    // $this->is_redis_connected will be set inside connect_redis()
                    if ($this->connect_redis()) {
                        return call_user_func_array([$this, 'call_redis'], array_merge([$method], $arguments));
                    }
                    // Fall through to fallback below
                } else {
                    throw $e;
                }
            }
        }

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

        return false;
    }
}

WP_Object_Cache::initialize();
