<?php

require_once __DIR__ . '/../set/SetManagedTest.php';

// wp_cache_set() with expiration to an existing managed key.
class SetExManagedTest extends SetManagedTest {
    use ExamineCacheSetEx;
}
