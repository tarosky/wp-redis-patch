<?php

require_once __DIR__ . '/../set/SetInexistentRacingTest.php';

// Race condition occurred during wp_cache_set() with expiration to an inexistent key.
class SetExInexistentRacingTest extends SetInexistentRacingTest {
    use ExamineCacheSetEx;
}
