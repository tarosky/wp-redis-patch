<?php

// Race condition occurred during wp_cache_tarosky_get_multiple() an inexistent unversioned value.
class GetMInexistentUnversionedRacingTest extends ObjectCacheTestCase {
	public function setUp(): void {
		$this->set_next_versioning_keys( array() );
		parent::setUp();

		$this->set_value();
	}

	use ExamineGetMVer;
	use TestExamGetMVer;
	use TestRedisValExists;
	use TestRedisVersionNotExist; // Not set
	use TestInternalVersionNotExist;
	use TestInternalValExists; // Set
	use ConnectionTests;
}
