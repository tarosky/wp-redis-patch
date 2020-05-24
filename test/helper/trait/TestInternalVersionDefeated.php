<?php

trait TestInternalVersionDefeated {
    public function testInternalVersionDefeated() {
        $this->examined();

        $redis_key = $this->redis_key(self::KEY);
        $this->assertEquals(self::RIVAL_VERSION, $this->cache_version($redis_key));
    }
}
