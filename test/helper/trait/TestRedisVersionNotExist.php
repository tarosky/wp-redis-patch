<?php

trait TestRedisVersionNotExist {
	public function testRedisVersionNotExist() {
		$this->examined();

		$this->assertFalse( self::$redis->get( $this->version_key( self::KEY ) ) );
	}
}
