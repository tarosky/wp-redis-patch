<?php

trait ExamineCacheDelete {
    protected function examined() {
        switch (wp_cache_delete(self::KEY)) {
            case 0:
                return false;
            case 1:
                return true;
            default:
                $this->fail('unexpected return value');
                return false;
        }
    }
}
