<?php

require_once __DIR__ . '/../replace/ReplaceInexistentRacingTest.php';

// Race condition occurred during wp_cache_replace() with expiration as an inexistent key.
class ReplaceExInexistentRacingTest extends ReplaceInexistentRacingTest {
    use ExamineCacheReplaceEx;
}
