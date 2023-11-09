<?php

// wp_cache_get() an ignored key.
class GetIgnoredTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		$this->setup_ignored_key();
	}

	public function testExamFails() {
		$found = true;
		$res   = wp_cache_get( self::IGN_KEY, self::IGN_GROUP, false, $found );
		$this->assertFalse( $res );
		$this->assertFalse( $found );
	}
}
