<?php

require_once __DIR__ . '/../add/AddManagedRacingTest.php';

// Race condition occurred during wp_cache_add() with expiration to an existing managed key before exists() call.
class AddExManagedRacingTest extends AddManagedRacingTest {
    use ExamineCacheAddEx;
}
