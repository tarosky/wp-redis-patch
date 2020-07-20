<?php

// wp_cache_set() to an existing managed key.
class SetManagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL_SUP);
        $this->get_and_store_version();
    }

    use ExamineCacheSet;
    use TestExamSucceeds;
    use TestRedisValExists; // Updated
    use TestRedisVersionRenewed; // Updated
    use TestInternalVersionLatest;
    use TestInternalValExists; // Updated
    use ConnectionTests;
}
