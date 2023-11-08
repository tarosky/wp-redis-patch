<?php

trait ConnectionTests {
	public function testConnectionKept() {
		$this->examined();

		$this->assertTrue( $this->connection_kept() );
	}
}
