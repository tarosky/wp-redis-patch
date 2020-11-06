<?php

class AlloptionsTest extends ObjectCacheTestCase {
    public function testEmptyPrecondition() {
        $this->assertFalse($this->oc1->get(self::KEY, '', true));
        $this->assertTrue($this->oc1->add(self::KEY, self::VAL));
    }

    public function testEmptyPreconditionPar() {
        $this->assertFalse($this->oc1->get(self::KEY, '', true));
        $this->assertFalse($this->oc2->get(self::KEY, '', true));
        $this->assertTrue($this->oc1->add(self::KEY, self::VAL));
        $this->assertFalse($this->oc2->add(self::KEY, self::VAL));
        $this->assertEquals(self::VAL, $this->oc2->get(self::KEY, '', true));
    }

    public function testEmptyValuePrecondition() {
        self::$redis->set(
            $this->oc1->version_key(self::KEY),
            $this->oc1->generate_version());

        $this->assertFalse($this->oc1->get(self::KEY, '', true));
        $this->assertTrue($this->oc1->add(self::KEY, self::VAL));
    }

    public function testEmptyVersionPrecondition() {
        self::$redis->set(
            $this->oc1->redis_key(self::KEY),
            TaroskyObjectCache::encode_redis_string(self::VAL_SUP));

        $this->assertEquals(self::VAL_SUP, $this->oc1->get(self::KEY, '', true));
    }
}
