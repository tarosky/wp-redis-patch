<?php

// wp_cache_set() an ignored key.
class SetIgnoredTest extends ObjectCacheTestCase {
	protected function examined() {
		return wp_cache_set( self::IGN_KEY, self::VAL, self::IGN_GROUP );
	}

	use TestExamSucceeds;
	use TestRedisIgnoredValNotExist;
}
