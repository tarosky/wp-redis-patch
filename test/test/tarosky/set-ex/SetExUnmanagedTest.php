<?php

require_once __DIR__ . '/../set/SetUnmanagedTest.php';

// wp_cache_set() with expiration to an existing unmanaged key.
class SetExUnmanagedTest extends SetUnmanagedTest {
    use ExamineCacheSetEx;
}
