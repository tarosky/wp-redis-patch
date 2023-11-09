<?php

trait ExamineCacheReplace {
	protected function examined() {
		return wp_cache_replace( self::KEY, self::VAL );
	}
}
