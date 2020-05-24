<?php

// Race condition occurred during wp_cache_delete()-ing an existing managed key.
class DeleteManagedRacingSetTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        wp_cache_set(self::KEY, self::VAL);
        $this->get_and_store_version();
        $this->set_rival_version();
        $this->set_sup_value();
    }

    use ExamineCacheDelete;
    use TestExamSucceeds;
    use TestRedisValNotExist; // Removed
    use TestRedisVersionRenewed; // Updated
    use TestInternalVersionLatest;
    use TestInternalValNotExist;
    use ConnectionTests;
}
