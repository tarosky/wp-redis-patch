<?php

// wp_cache_set() to an inexistent key.
class SetInexistentTest extends ObjectCacheTestCase {
    use ExamineCacheSet;
    use TestExamSucceeds;
    use TestRedisValExists; // Set
    use TestRedisVersionRenewed; // Set
    use TestInternalVersionLatest;
    use TestInternalValExists; // Set
    use ConnectionTests;
}
