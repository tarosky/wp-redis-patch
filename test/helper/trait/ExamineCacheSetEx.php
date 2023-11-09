<?php

trait ExamineCacheSetEx {
	protected function examined() {
		return wp_cache_set( self::KEY, self::VAL, self::GROUP, 10 );
	}
}
