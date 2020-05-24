<?php

// wp_cache_get() an existing managed value.
class GetManagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL);
        $this->get_and_store_version();
    }

    use ExamineCacheGet;
    use TestExamGetSucceeds;
    use TestRedisValExists; // Not changed
    use TestRedisVersionNotChanged;
    use TestInternalVersionOld;
    use TestInternalValExists;
    use ConnectionTests;
}
