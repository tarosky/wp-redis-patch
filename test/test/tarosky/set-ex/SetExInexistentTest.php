<?php

require_once __DIR__ . '/../set/SetInexistentTest.php';

// wp_cache_set() with expiration to an inexistent key.
class SetExInexistentTest extends SetInexistentTest {
    use ExamineCacheSetEx;
}
