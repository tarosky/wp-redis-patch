<?php

// wp_cache_set() to an existing unmanaged key.
class SetUnmanagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_sup_value();
    }

    use ExamineCacheSet;
    use TestExamSucceeds;
    use TestRedisValExists; // Updated
    use TestRedisVersionRenewed; // Updated
    use TestInternalVersionLatest;
    use TestInternalValExists; // Set
    use ConnectionTests;
}
