<?php

trait TestInternalVersionNotExist {
    public function testInternalVersionNotExist() {
        $this->examined();

        $redis_key = $this->redis_key(self::KEY);
        $this->assertNull($this->cache_version($redis_key));
    }
}
