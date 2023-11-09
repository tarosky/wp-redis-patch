<?php

trait ExamineCacheDelete {
	protected function examined() {
		return wp_cache_delete( self::KEY );
	}
}
