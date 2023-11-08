<?php

trait TestRedisValNotExist {
	public function testRedisValNotExist() {
		$this->examined();
		$this->assertFalse( self::$redis->get( $this->key( self::KEY ) ) );
	}
}
