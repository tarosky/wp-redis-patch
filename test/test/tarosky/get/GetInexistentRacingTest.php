<?php

// Race condition occurred during wp_cache_get()-ing an inexistent value.
class GetInexistentRacingTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		$this->set_rival_version();
		$this->set_value();
	}

	use ExamineCacheGet;
	use TestExamGetSucceeds;
	use TestRedisValExists; // Set
	use TestRedisVersionDefeated; // Set
	use TestInternalVersionDefeated;
	use TestInternalValExists;
	use ConnectionTests;
}
