<?php

trait TestExamGetMVer {
	public function testExamGetMVer() {
		$this->assertEqualsCanonicalizing(
			json_encode( array( self::GROUP => array( self::KEY => self::VAL ) ), JSON_PRETTY_PRINT ),
			json_encode( $this->examined(), JSON_PRETTY_PRINT ),
		);
	}
}
