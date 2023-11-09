<?php

require_once __DIR__ . '/../add/AddInexistentRacingTest.php';

// Race condition occurred during wp_cache_add() with expiration as an inexistent key before exists() call.
class AddExInexistentRacingTest extends AddInexistentRacingTest {
	use ExamineCacheAddEx;
}
