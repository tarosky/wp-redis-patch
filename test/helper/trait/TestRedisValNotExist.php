<?php

trait TestRedisValNotExist {
    public function testRedisValNotExist() {
        $this->examined();
        $this->assertFalse(self::$redis->get($this->redis_key(self::KEY)));
    }
}
