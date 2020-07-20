<?php

trait TestInternalValExists {
    public function testInternalValExists() {
        $this->examined();

        $redis_key = $this->redis_key(self::KEY);
        $this->assertEquals(self::VAL, $this->cache()[$redis_key]);
    }
}
