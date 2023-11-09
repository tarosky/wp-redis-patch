<?php

trait TestRedisValExists {
	public function testRedisValExists() {
		$this->examined();
		$this->assertRedisEquals( self::VAL, self::KEY );
	}
}
