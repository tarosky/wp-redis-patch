<?php

trait ExamineCacheSet {
    protected function examined() {
        return wp_cache_set(self::KEY, self::VAL);
    }
}
