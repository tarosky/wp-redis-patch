<?php

// Race condition occurred during forcibly wp_cache_tarosky_get_multiple() to
// an existing managed versioned value.
class GetMManagedVersionedRacingForceTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		wp_cache_set( self::KEY, self::VAL_SUP );
		$this->get_and_store_version();
		$this->set_rival_version();
		$this->set_value();
	}

	protected function examined() {
		return wp_cache_tarosky_get_multiple( array( self::GROUP => array( self::KEY ) ), true );
	}

	use TestExamGetMVer;
	use TestRedisValExists;
	use TestRedisVersionDefeated;
	use TestInternalVersionDefeated;
	use TestInternalValExists;
	use ConnectionTests;
}
