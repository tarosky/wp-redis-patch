<?php

trait ExamineCacheReplaceEx {
	protected function examined() {
		return wp_cache_replace( self::KEY, self::VAL, self::GROUP, 10 );
	}
}
