<?php

trait TestInternalVersionOld {
    public function testInternalVersionOld() {
        $this->examined();

        $this->assertEquals(
            $this->old_version,
            $this->cache_version($this->redis_key(self::KEY)),
        );
    }
}
