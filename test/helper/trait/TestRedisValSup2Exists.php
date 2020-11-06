<?php

trait TestRedisValSup2Exists {
    public function testRedisValSupExists() {
        $this->examined();
        $this->assertRedisEquals(self::VAL_SUP_2, self::KEY);
    }
}
