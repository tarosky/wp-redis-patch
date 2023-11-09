<?php

trait TestRedisVersionRenewed {
	public function testRedisVersionRenewed() {
		$this->examined();

		$version = self::$redis->get( $this->version_key( self::KEY ) );
		$this->assertNotEquals( self::RIVAL_VERSION, $version );
		$this->assertNotEquals( $this->old_version, $version );
		$this->assertStringMatchesFormat( self::VERSION_FORMAT, $version );
	}
}
