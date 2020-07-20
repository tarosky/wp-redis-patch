<?php

// Race condition occurred during wp_cache_replace() as an inexistent key.
class ReplaceInexistentRacingTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_rival_version();
        $this->set_sup_value();
    }

    use ExamineCacheReplace;
    use TestExamFails;
    use TestRedisValNotExist; // Removed
    use TestRedisVersionRenewed; // Updated
    use TestInternalVersionNotExist;
    use TestInternalValNotExist; // Not changed
    use ConnectionTests;
}
