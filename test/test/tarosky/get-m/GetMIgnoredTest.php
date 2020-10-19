<?php

// wp_cache_tarosky_get_multiple() an ignored key.
class GetMIgnoredTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->setup_ignored_key();
    }

    protected function examined() {
        return wp_cache_tarosky_get_multiple([self::GROUP => [self::KEY]]);
    }

    public function testGetM() {
        $this->assertEquals([], $this->examined());
    }
}
