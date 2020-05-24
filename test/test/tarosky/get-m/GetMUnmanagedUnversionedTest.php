<?php

// wp_cache_tarosky_get_multiple() to an existing unmanaged unversioned value.
class GetMUnmanagedUnversionedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        $this->set_next_versioning_keys([]);
        parent::setUp();

        $this->set_value();
    }

    use ExamineGetMVer;
    use TestExamGetMVer;
    use TestRedisValExists;
    use TestRedisVersionNotExist; // Not updated
    use TestInternalVersionNotExist;
    use TestInternalValExists; // Set
    use ConnectionTests;
}
