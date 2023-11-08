<?php

// Race condition occurred during forcibly wp_cache_get()-ing an existing managed value.
class GetManagedRacingForceTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		wp_cache_set( self::KEY, self::VAL_SUP );
		$this->get_and_store_version();
		$this->set_rival_version();
		$this->set_value();
	}

	protected function examined() {
		return wp_cache_get( self::KEY, self::GROUP, true );
	}

	use TestExamGetSucceeds;
	use TestRedisValExists; // Not changed
	use TestRedisVersionDefeated; // Not changed
	use TestInternalVersionDefeated;
	use TestInternalValExists;
	use ConnectionTests;
}
