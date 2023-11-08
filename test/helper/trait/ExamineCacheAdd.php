<?php

trait ExamineCacheAdd {
	protected function examined() {
		return wp_cache_add( self::KEY, self::VAL );
	}
}
