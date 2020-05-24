<?php

// wp_cache_delete() an inexistent key.
class DeleteInexistentTest extends ObjectCacheTestCase {
    use ExamineCacheDelete;
    use TestExamFails;
    use TestRedisValNotExist;
    use TestRedisVersionRenewed; // Set
    use TestInternalVersionLatest;
    use TestInternalValNotExist;
    use ConnectionTests;
}
