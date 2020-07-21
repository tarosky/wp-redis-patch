<?php

trait TestRedisVersionDefeated {
    public function testRedisVersionDefeated() {
        $this->examined();

        $this->assertEquals(
            self::RIVAL_VERSION,
            self::$redis->get($this->version_key(self::KEY)),
        );
    }
}
