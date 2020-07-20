<?php

trait TestInternalVersionOld {
    public function testInternalVersionOld() {
        $this->examined();

        $redis_key = $this->redis_key(self::KEY);
        $this->assertEquals($this->old_version, $this->cache_version($redis_key));
    }
}
