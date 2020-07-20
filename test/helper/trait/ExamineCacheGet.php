<?php

trait ExamineCacheGet {
    protected function examined() {
        return wp_cache_get(self::KEY);
    }
}
