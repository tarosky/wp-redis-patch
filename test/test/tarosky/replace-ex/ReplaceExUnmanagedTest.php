<?php

require_once __DIR__ . '/../replace/ReplaceUnmanagedTest.php';

// wp_cache_replace() with expiration to an existing unmanaged key.
class ReplaceExUnmanagedTest extends ReplaceUnmanagedTest {
	use ExamineCacheReplaceEx;
}
