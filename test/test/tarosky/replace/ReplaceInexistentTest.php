<?php

// wp_cache_replace() as an inexistent key.
class ReplaceInexistentTest extends ObjectCacheTestCase {
    use ExamineCacheReplace;
    use TestExamFails;
    use TestRedisValNotExist; // Data won't be changed because of the original specification.
    use TestRedisVersionNotExist; // Not updated
    use TestInternalVersionNotExist;
    use TestInternalValNotExist; // Not changed
    use ConnectionTests;
}
