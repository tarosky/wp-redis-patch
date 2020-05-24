<?php

trait TestRedisValSupExists {
    public function testRedisValSupExists() {
        $this->examined();
        $this->assertRedisEquals(self::VAL_SUP, self::KEY);
    }
}
