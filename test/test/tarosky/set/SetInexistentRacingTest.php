<?php

// Race condition occurred during wp_cache_set() to an inexistent key.
class SetInexistentRacingTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		$this->set_rival_version();
		$this->set_sup_value();
	}

	use ExamineCacheSet;
	use TestExamFails;
	use TestRedisValNotExist;
	use TestRedisVersionNotExist;
	use TestInternalVersionNotExist;
	use TestInternalValNotExist; // Removed
	use ConnectionTests;
}
