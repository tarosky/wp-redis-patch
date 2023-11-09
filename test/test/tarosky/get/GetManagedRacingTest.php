<?php

// Race condition occurred during wp_cache_get()-ing an existing managed value.
class GetManagedRacingTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		wp_cache_set( self::KEY, self::VAL );
		$this->get_and_store_version();
		$this->set_rival_version();
		$this->set_sup_value();
	}

	use ExamineCacheGet;
	use TestExamGetSucceeds; // Old value
	use TestRedisValSupExists; // Not changed
	use TestRedisVersionDefeated; // Not changed
	use TestInternalVersionOld;
	use TestInternalValExists;
	use ConnectionTests;
}
