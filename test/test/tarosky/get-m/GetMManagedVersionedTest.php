<?php

// wp_cache_tarosky_get_multiple() to an existing managed versioned value.
class GetMManagedVersionedTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		wp_cache_set( self::KEY, self::VAL );
		$this->get_and_store_version();
	}

	use ExamineGetMVer;
	use TestExamGetMVer;
	use TestRedisValExists; // Not updated
	use TestRedisVersionNotChanged;
	use TestInternalVersionOld;
	use TestInternalValExists;
	use ConnectionTests;
}
