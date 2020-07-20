<?php

// wp_cache_get() an inexistent value.
class GetInexistentTest extends ObjectCacheTestCase {
    use ExamineCacheGet;
    use TestExamFails;
    use TestRedisValNotExist; // Not changed
    use TestRedisVersionNotExist; // Not changed
    use TestInternalVersionNotExist;
    use TestInternalValNotExist;
    use ConnectionTests;
}
