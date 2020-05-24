<?php

// wp_cache_add() to an existing unmanaged key.
class AddUnmanagedTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_sup_value();
    }

    use ExamineCacheAdd;
    use TestExamFails;
    use TestRedisValSupExists; // Data won't be changed because of the original specification.
    use TestRedisVersionNotExist; // Not set
    use TestInternalVersionNotExist;
    use TestInternalValNotExist; // Not changed
    use ConnectionTests;
}
