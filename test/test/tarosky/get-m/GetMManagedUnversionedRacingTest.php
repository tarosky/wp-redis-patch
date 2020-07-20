<?php

// Race condition occurred during wp_cache_tarosky_get_multiple() to an existing managed unversioned value.
class GetMManagedUnversionedRacingTest extends ObjectCacheTestCase {
    public function setUp(): void {
        $this->set_next_versioning_keys([]);
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL);
        $this->set_sup_value();
    }

    use ExamineGetMVer;
    use TestExamGetMVer;
    use TestRedisValSupExists; // Not changed
    use TestRedisVersionNotExist; // Not set
    use TestInternalVersionNotExist;
    use TestInternalValExists; // Not changed
    use ConnectionTests;
}
