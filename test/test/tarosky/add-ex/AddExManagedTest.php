<?php

require_once __DIR__ . '/../add/AddManagedTest.php';

// wp_cache_add() with expiration to an existing managed key.
class AddExManagedTest extends AddManagedTest {
    use ExamineCacheAddEx;
}
