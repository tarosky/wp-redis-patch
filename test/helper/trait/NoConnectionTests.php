<?php

trait NoConnectionTests {
    public function testNoConnectionKept() {
        $this->examined();

        $this->assertNull($this->connection_kept());
    }
}
