<?php

require_once __DIR__ . '/../set/SetManagedRacingTest.php';

// Race condition occurred during wp_cache_set() with expiration to an existing managed key.
class SetExManagedRacingTest extends SetManagedRacingTest {
	use ExamineCacheSetEx;
}
