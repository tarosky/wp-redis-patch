<?php

trait TestExamSucceeds {
    public function testExamSucceeds() {
        $this->assertTrue($this->examined());
    }
}
