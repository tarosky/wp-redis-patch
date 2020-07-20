<?php

// wp_cache_replace() to an existing unmanaged key.
class ReplaceUnmanagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_sup_value();
    }

    use ExamineCacheReplace;
    use TestExamSucceeds;
    use TestRedisValExists; // Updated
    use TestRedisVersionRenewed; // Set
    use TestInternalVersionLatest;
    use TestInternalValExists; // Set
    use ConnectionTests;
}
