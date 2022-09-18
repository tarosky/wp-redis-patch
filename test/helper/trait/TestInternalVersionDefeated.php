<?php

trait TestInternalVersionDefeated {
    public function testInternalVersionDefeated() {
        $this->examined();

        $this->assertEquals(
            self::RIVAL_VERSION,
            $this->cache_version($this->key(self::KEY)),
        );
    }
}
