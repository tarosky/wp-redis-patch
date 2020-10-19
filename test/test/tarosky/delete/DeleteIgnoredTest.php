<?php

// wp_cache_delete() doesn't ignore ignored keys.
class DeleteIgnoredTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->setup_ignored_key();
    }

    protected function examined() {
        return wp_cache_delete(self::IGN_KEY, self::IGN_GROUP);
    }

    use TestExamSucceeds;
    use TestRedisIgnoredValNotExist;
}
