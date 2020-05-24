<?php

// wp_cache_tarosky_get_multiple() to an existing managed unversioned value.
class GetMManagedUnversionedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        $this->set_next_versioning_keys([]);
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL);
    }

    use ExamineGetMVer;
    use TestExamGetMVer;
    use TestRedisValExists;
    use TestRedisVersionNotExist; // Not updated
    use TestInternalVersionNotExist;
    use TestInternalValExists; // Not updated
    use ConnectionTests;
}
