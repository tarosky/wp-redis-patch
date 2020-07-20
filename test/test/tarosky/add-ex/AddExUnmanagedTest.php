<?php

require_once __DIR__ . '/../add/AddUnmanagedTest.php';

// wp_cache_add() with expiration to an existing unmanaged key.
class AddExUnmanagedTest extends AddUnmanagedTest {
    use ExamineCacheAddEx;
}
