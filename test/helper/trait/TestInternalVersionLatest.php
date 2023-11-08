<?php

trait TestInternalVersionLatest {
	public function testInternalVersionLatest() {
		$this->examined();

		$redis_key = $this->key( self::KEY );
		$version   = self::$redis->get( $this->version_key( self::KEY ) );
		$this->assertEquals( $version, $this->cache_version( $redis_key ) );
	}
}
