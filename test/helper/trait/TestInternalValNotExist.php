<?php

trait TestInternalValNotExist {
    public function testInternalValNotExist() {
        $this->examined();

        $redis_key = $this->key(self::KEY);
        $this->assertArrayNotHasKey($redis_key, $this->cache());
    }
}
