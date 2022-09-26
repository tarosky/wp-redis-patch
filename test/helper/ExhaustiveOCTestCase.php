<?php

use PHPUnit\Framework\TestCase;

abstract class ExhaustiveOCTestCase extends TestCase {
    protected static $redis;
    protected const GROUP = 'default';
    protected const KEY = 'alloptions';
    protected const VAL = 'sample-value';
    protected const VAL_SUP = 'another-value';
    protected $ocs;

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();

        global $redis_server;
        $redis_server = [
            'host' => 'redis',
            'port' => 6379,
            'timeout' => 1000,
            'retry_interval' => 100,
        ];

        self::$redis = new Redis();
        self::$redis->connect('redis');
    }

    public function setUp(): void {
        global $redis_server_default_versioning_keys, $redis_server_versioning_keys;
        self::$redis->flushdb();
        $redis_server_versioning_keys = $redis_server_default_versioning_keys;
    }
}
