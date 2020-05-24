<?php

// wp_cache_add() to an existing managed key.
class AddManagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL_SUP);
        $this->get_and_store_version();
    }

    use ExamineCacheAdd;
    use TestExamFails;
    use TestRedisValSupExists; // Not changed
    use TestRedisVersionNotChanged;
    use TestInternalVersionNotExist;
    use TestInternalValNotExist; // Removed
    use ConnectionTests;
}
