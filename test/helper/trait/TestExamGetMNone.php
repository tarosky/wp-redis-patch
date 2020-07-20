<?php

trait TestExamGetMNone {
    public function testExamGetMNone() {
        $this->assertEquals(
            json_encode([], JSON_PRETTY_PRINT),
            json_encode($this->examined(), JSON_PRETTY_PRINT),
        );
    }
}
