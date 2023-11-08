<?php

trait TestRedisIgnoredValExists {
	public function testRedisIgnoredValExists() {
		$this->examined();
		$this->assertRedisEquals( self::VAL, self::IGN_KEY, self::IGN_GROUP );
	}
}
