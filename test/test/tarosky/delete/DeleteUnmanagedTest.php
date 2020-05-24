<?php

// wp_cache_delete() an existing unmanaged key.
class DeleteUnmanagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_value();
    }

    use ExamineCacheDelete;
    use TestExamSucceeds;
    use TestRedisValNotExist; // Removed
    use TestRedisVersionRenewed; // Updated
    use TestInternalVersionLatest;
    use TestInternalValNotExist;
    use ConnectionTests;
}
