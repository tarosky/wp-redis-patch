<?php

// wp_cache_get() an existing unmanaged value.
class GetUnmanagedTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		$this->set_value();
	}

	use ExamineCacheGet;
	use TestExamGetSucceeds;
	use TestRedisValExists; // Not changed
	use TestRedisVersionNotExist; // Not changed
	use TestInternalVersionNotExist;
	use TestInternalValExists; // Set
	use ConnectionTests;
}
