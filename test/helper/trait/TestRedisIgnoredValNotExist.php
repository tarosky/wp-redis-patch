<?php

trait TestRedisIgnoredValNotExist {
    public function testRedisIgnoredValNotExist() {
        $this->examined();
        $this->assertRedisNonExistent(self::IGN_KEY, self::IGN_GROUP);
    }
}
