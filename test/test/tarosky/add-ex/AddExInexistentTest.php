<?php

require_once __DIR__ . '/../add/AddInexistentTest.php';

// wp_cache_add() with expiration as an inexistent key.
class AddExInexistentTest extends AddInexistentTest {
	use ExamineCacheAddEx;
}
