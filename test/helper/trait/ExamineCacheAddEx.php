<?php

trait ExamineCacheAddEx {
	protected function examined() {
		return wp_cache_add( self::KEY, self::VAL, self::GROUP, 10 );
	}
}
