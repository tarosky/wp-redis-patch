<?php

require_once WP_REDIS_PLUGIN_DIR . '/object-cache.php';

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

class TaroskyObjectCache extends WP_Object_Cache {
    private static $lua_scripts = [
        'decr-by-nover' => [],
        'del' => [],
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

    private $raw_client;

    private static function debug($message, ...$params) {
        if (defined('TAROSKY_WP_REDIS_PATCH_DEBUG') && TAROSKY_WP_REDIS_PATCH_DEBUG) {
            error_log("[TaroskyObjectCache debug]$message: " . var_export($params, true));
        }
    }

    private static function error($message, ...$params) {
        error_log("[TaroskyObjectCache error]$message: " . var_export($params, true));
        error_log(
            "[TaroskyObjectCache error]stacktrace: " .
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

    // override
    protected function _should_use_redis_hashes($group) {
        return false;
    }

    // override
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

        return WP_CACHE_KEY_SALT . json_encode([$prefix, $group, $key]);
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
    // override
    public function delete_group($group) {
        return false;
    }

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

    public function __construct() {
        parent::__construct();
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

        return WP_CACHE_VERSION_KEY_SALT . json_encode([$prefix, $group, $key]);
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

    // override
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

    public function replace(
        $key,
        $data,
        $group = 'default',
        $expire = WP_REDIS_DEFAULT_EXPIRE_SECONDS
    ) {
        if (empty($group)) {
            $group = 'default';
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

    public function get($key, $group = 'default', $force = false, &$found = null) {
        if (empty($group)) {
            $group = 'default';
        }

        try {
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

                [$res, $actual_version] = $this->_call_redis('mget', [
                    $redis_key,
                    $this->_version_key($key, $group),
                ]);

                if ($actual_version === false) {
                    $this->clear_cache_version($redis_key);
                } else {
                    $this->set_cache_version($redis_key, $actual_version);
                }

                list($value, $found) = self::decode_redis_get($res);
                return $value;
            } finally {
                if ($found) {
                    $this->_set_internal($key, $group, $value);
                } else {
                    $this->_unset_internal($key, $group);
                }
            }
        } finally {
            if ($found) {
                $this->cache_hits += 1;
            } else {
                $this->cache_misses += 1;
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

            if ($found) {
                $this->cache_hits += 1;
            } else {
                $this->cache_misses += 1;
            }

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
                    list($value, $found) = $get_from_cache($key, $group);
                    if ($found) {
                        $cache[$group][$key] = $value;
                    }
                }
                continue;
            }

            foreach ($keys as $key) {
                if (!$force && $this->_isset_internal($key, $group)) {
                    $this->cache_hits += 1;
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

                    if ($found) {
                        $this->cache_hits += 1;
                    } else {
                        $this->cache_misses += 1;
                    }

                    return null;
                },
                array_map([$this, 'decode_redis_get'], $redis_result),
                $redis_group_key_indexes,
            );
        }

        return $cache;
    }

    // `force` param is ignored.
    public function delete($key, $group = 'default', $force = true) {
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

            $new_version = $this->generate_version();
            $this->set_cache_version($redis_key, $new_version);

            $res = $this->_call_redis(
                'evalSha',
                self::$lua_scripts['del']['hash'],
                [$redis_key, $this->_version_key($key, $group), $new_version],
                2,
            );
            return self::decode_redis_del($res) == 1;
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

    public function decr($key, $offset = 1, $group = 'default') {
        if (empty($group)) {
            $group = 'default';
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

    public function incr($key, $offset = 1, $group = 'default') {
        if (empty($group)) {
            $group = 'default';
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

    public function flush($redis = true) {
        $this->flush_cache_versions();
        return parent::flush($redis);
    }

    public function _call_redis($method, ...$args) {
        return parent::_call_redis($method, ...$args);
    }
}

TaroskyObjectCache::initialize();
