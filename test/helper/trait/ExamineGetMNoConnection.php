<?php

trait ExamineGetMNoConnection {
    protected function examined() {
        global $wp_object_cache;

        $wp_object_cache->is_redis_connected = false;

        return wp_cache_tarosky_get_multiple([self::GROUP => [self::KEY]]);
    }
}
