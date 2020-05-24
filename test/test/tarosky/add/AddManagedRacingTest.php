<?php

// Race condition occurred during wp_cache_add() to an existing managed key before exists() call.
class AddManagedRacingTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL_SUP);
        $this->get_and_store_version();
        $this->set_rival_version();
        $this->set_sup_2_value();
    }

    use ExamineCacheAdd;
    use TestExamFails;
    use TestRedisValNotExist; // Removed
    use TestRedisVersionRenewed; // Set
    use TestInternalVersionNotExist;
    use TestInternalValNotExist; // Removed
    use ConnectionTests;
}
