<?php

// Race condition occurred during wp_cache_delete()-ing an inexistent key.
class DeleteInexistentRacingTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		$this->set_rival_version();
		$this->set_sup_value();
	}

	use ExamineCacheDelete;
	use TestExamSucceeds;
	use TestRedisValNotExist;
	use TestRedisVersionNotExist;
	use TestInternalVersionNotExist;
	use TestInternalValNotExist;
	use ConnectionTests;
}
