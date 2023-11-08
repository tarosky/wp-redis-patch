<?php

// wp_cache_add() as an inexistent key.
class AddInexistentTest extends ObjectCacheTestCase {
	use ExamineCacheAdd;
	use TestExamSucceeds;
	use TestRedisValExists; // Added
	use TestRedisVersionRenewed; // Added
	use TestInternalVersionLatest;
	use TestInternalValExists;
	use ConnectionTests;
}
