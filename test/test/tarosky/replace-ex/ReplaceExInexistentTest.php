<?php

require_once __DIR__ . '/../replace/ReplaceInexistentTest.php';

// wp_cache_replace() with expiration as an inexistent key.
class ReplaceExInexistentTest extends ReplaceInexistentTest {
	use ExamineCacheReplaceEx;
}
