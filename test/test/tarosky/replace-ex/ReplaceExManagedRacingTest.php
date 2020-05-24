<?php

require_once __DIR__ . '/../replace/ReplaceManagedRacingTest.php';

// Race condition occurred during wp_cache_replace() with expiration to an existing managed key.
class ReplaceExManagedRacingTest extends ReplaceManagedRacingTest {
    use ExamineCacheReplaceEx;
}
