<?php

// wp_cache_tarosky_get_multiple() an inexistent unversioned value.
class GetMInexistentUnversionedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        $this->set_next_versioning_keys([]);
        parent::setUp();
    }

    use ExamineGetMVer;
    use TestExamGetMNone;
    use TestRedisValNotExist;
    use TestRedisVersionNotExist; // Not set
    use TestInternalVersionNotExist;
    use TestInternalValNotExist;
    use ConnectionTests;
}
