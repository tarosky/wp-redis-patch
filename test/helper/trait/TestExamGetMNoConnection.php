<?php

trait TestExamGetMNoConnection {
	public function testExamGetMNoConnection() {
		$this->assertEqualsCanonicalizing(
			json_encode( array(), JSON_PRETTY_PRINT ),
			json_encode( $this->examined(), JSON_PRETTY_PRINT ),
		);
	}
}
