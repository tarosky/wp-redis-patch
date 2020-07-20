<?php

require_once __DIR__ . '/../replace/ReplaceManagedTest.php';

// wp_cache_replace() with expiration to an existing managed key.
class ReplaceExManagedTest extends ReplaceManagedTest {
    use ExamineCacheReplaceEx;
}
