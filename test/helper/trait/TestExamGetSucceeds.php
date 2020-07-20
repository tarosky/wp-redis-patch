<?php

trait TestExamGetSucceeds {
    public function testExamGetSucceeds() {
        $this->assertEquals(self::VAL, $this->examined());
    }
}
