<?php

trait NoConnectionTests {
    public function testNoConnectionKept() {
        $this->examined();

        $this->assertFalse($this->connection_kept());
    }
}
