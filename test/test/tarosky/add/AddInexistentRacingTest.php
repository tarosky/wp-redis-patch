<?php

// Race condition occurred during wp_cache_add() as an inexistent key.
class AddInexistentRacingTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_rival_version();
        $this->set_sup_value();
    }

    use ExamineCacheAdd;
    use TestExamFails;
    use TestRedisValSupExists;
    use TestRedisVersionDefeated;
    use TestInternalVersionNotExist;
    use TestInternalValNotExist; // Internal cache won't be changed because the data keeps unchanged.
    use ConnectionTests;
}
