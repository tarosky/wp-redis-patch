<?php

class ScriptTest extends ObjectCacheTestCase {
	public function setUp(): void {
		parent::setUp();

		self::$redis->flushdb();
		self::$redis->script( 'flush' );
	}

	public function testScriptsLoaded() {
		global $wp_object_cache;

		$hashes = array_map(function ( $path ) {
			return sha1( file_get_contents( $path ) );
		}, glob( TAROSKY_WP_REDIS_PATCH_LUA_DIR . '/*.lua' ));

		foreach ( $hashes as $hash ) {
			$this->assertFalse( ! ! self::$redis->script( 'exists', $hash )[0] );
		}

		$wp_object_cache->ensureLua( self::$redis );

		foreach ( $hashes as $hash ) {
			$this->assertTrue( ! ! self::$redis->script( 'exists', $hash )[0] );
		}

		// Nothing happens when running it twice.
		$wp_object_cache->ensureLua( self::$redis );

		foreach ( $hashes as $hash ) {
			$this->assertTrue( ! ! self::$redis->script( 'exists', $hash )[0] );
		}
	}
}
