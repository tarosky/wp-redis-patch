<?php

// Race condition occurred during wp_cache_delete()-ing an existing managed key.
class DeleteManagedRacingDeleteTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		wp_cache_set( self::KEY, self::VAL );
		$this->get_and_store_version();
		$this->set_rival_version();
		$this->del_val();
	}

	use ExamineCacheDelete;
	use TestExamSucceeds;
	use TestRedisValNotExist;
	use TestRedisVersionNotExist;
	use TestInternalVersionNotExist;
	use TestInternalValNotExist;
	use ConnectionTests;
}
