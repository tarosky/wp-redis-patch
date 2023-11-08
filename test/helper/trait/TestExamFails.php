<?php

trait TestExamFails {
	public function testExamFails() {
		$this->assertFalse( $this->examined() );
	}
}
