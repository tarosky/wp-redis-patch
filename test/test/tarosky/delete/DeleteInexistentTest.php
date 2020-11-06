<?php

// wp_cache_delete() an inexistent key.
class DeleteInexistentTest extends ObjectCacheTestCase {
    use ExamineCacheDelete;
    use TestExamFails;
    use TestRedisValNotExist;
    use TestRedisVersionNotExist;
    use TestInternalVersionNotExist;
    use TestInternalValNotExist;
    use ConnectionTests;
}
