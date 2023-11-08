<?php

// wp_cache_delete() an existing managed key.
class DeleteManagedTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		wp_cache_set( self::KEY, self::VAL );
		$this->get_and_store_version();
	}

	use ExamineCacheDelete;
	use TestExamSucceeds;
	use TestRedisValNotExist;
	use TestRedisVersionNotExist;
	use TestInternalVersionNotExist;
	use TestInternalValNotExist;
	use ConnectionTests;
}
