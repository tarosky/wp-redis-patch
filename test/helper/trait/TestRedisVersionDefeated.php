<?php

trait TestRedisVersionDefeated {
    public function testRedisVersionDefeated() {
        $this->examined();

        $version = self::$redis->get($this->version_key(self::KEY));
        $this->assertEquals(self::RIVAL_VERSION, $version);
    }
}
