<?php

trait ExamineGetMVer {
	protected function examined() {
		return wp_cache_tarosky_get_multiple( array( self::GROUP => array( self::KEY ) ) );
	}
}
