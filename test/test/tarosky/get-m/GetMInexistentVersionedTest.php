<?php

// wp_cache_tarosky_get_multiple() an inexistent versioned value.
class GetMInexistentVersionedTest extends ObjectCacheTestCase {
	use ExamineGetMVer;
	use TestExamGetMNone;
	use TestRedisValNotExist; // Not changed
	use TestRedisVersionNotExist; // Not changed
	use TestInternalVersionNotExist;
	use TestInternalValNotExist; // Not set
	use ConnectionTests;
}
