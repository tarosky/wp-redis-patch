<?php

// Race condition occurred during wp_cache_tarosky_get_multiple() an inexistent versioned value.
class GetMInexistentVersionedRacingTest extends ObjectCacheTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->set_rival_version();
        $this->set_value();
    }

    use ExamineGetMVer;
    use TestExamGetMVer;
    use TestRedisValExists; // Not changed
    use TestRedisVersionDefeated; // Not changed
    use TestInternalVersionDefeated;
    use TestInternalValExists;
    use ConnectionTests;
}
