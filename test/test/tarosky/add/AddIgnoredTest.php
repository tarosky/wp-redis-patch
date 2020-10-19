<?php

// wp_cache_add() an ignored key.
class AddIgnoredTest extends ObjectCacheTestCase {
    protected function examined() {
        return wp_cache_add(self::IGN_KEY, self::VAL, self::IGN_GROUP);
    }

    use TestExamSucceeds;
    use TestRedisIgnoredValNotExist;
}
