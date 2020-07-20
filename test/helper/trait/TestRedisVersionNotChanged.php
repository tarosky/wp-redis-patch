<?php

trait TestRedisVersionNotChanged {
    public function testRedisVersionNotChanged() {
        $this->examined();

        $version = self::$redis->get($this->version_key(self::KEY));
        $this->assertEquals($this->old_version, $version);
    }
}
