<?php

// wp_cache_tarosky_get_multiple() when disconnected
class GetMNoConnectionTest extends ObjectCacheTestCase {
	public function setUp(): void {
		$this->set_next_versioning_keys( array() );
		parent::setUp();

		$this->set_value();
	}

	use ExamineGetMNoConnection;
	use TestExamGetMNoConnection;
	use TestRedisValExists;
	use TestRedisVersionNotExist; // Not updated
	use TestInternalVersionNotExist;
	use TestInternalValNotExist; // Not updated
	use NoConnectionTests;
}
