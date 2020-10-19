<?php

// wp_cache_replace() an ignored key.
class ReplaceIgnoredTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->setup_ignored_key();
    }

    protected function examined() {
        return wp_cache_replace(self::IGN_KEY, self::VAL_SUP);
    }

    use TestExamFails;
    use TestRedisIgnoredValExists;
}
