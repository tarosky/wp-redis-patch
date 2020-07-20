<?php

// wp_cache_replace() to an existing managed key.
class ReplaceManagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL_SUP);
        $this->get_and_store_version();
    }

    use ExamineCacheReplace;
    use TestExamSucceeds;
    use TestRedisValExists; // Replaced
    use TestRedisVersionRenewed; // Updated
    use TestInternalVersionLatest;
    use TestInternalValExists;
    use ConnectionTests;
}
