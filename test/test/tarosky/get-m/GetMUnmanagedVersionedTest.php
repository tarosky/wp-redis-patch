<?php

// wp_cache_tarosky_get_multiple() to an existing unmanaged versioned value.
class GetMUnmanagedVersionedTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		$this->set_value();
	}

	use ExamineGetMVer;
	use TestExamGetMVer;
	use TestRedisValExists; // Not updated
	use TestRedisVersionNotExist; // Not updated
	use TestInternalVersionNotExist;
	use TestInternalValExists; // Set
	use ConnectionTests;
}
